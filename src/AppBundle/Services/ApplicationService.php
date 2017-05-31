<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services;


use AppBundle\Entity\Application;
use AppBundle\Entity\Company;
use AppBundle\Entity\State;
use AppBundle\Entity\StatusModification;
use AppBundle\Services\Factories\SwiftMessageFactory;
use Doctrine\ORM\EntityManager;
use ReflectionClass;
use Swift_Mailer;
use Symfony\Component\HttpFoundation\Session\Session;

class ApplicationService
{
    private $em;
    private $tcs;
    private $session;
    private $message;
    private $mailer;

    public function __construct(
        EntityManager $em,
        TransitionConditionService $tcs,
        Session $session,
        SwiftMessageFactory $message,
        Swift_Mailer $mailer
    )
    {
        $this->em = $em;
        $this->tcs = $tcs;
        $this->session = $session;
        $this->message = $message;
        $this->mailer = $mailer;
    }

    public function setState(Application $application, array $data){
        $authoriseChangeState =true;
        $stateStart = $application->getCurrentState();
        //dump($application->getStudent()->getFirstName());die;
        if($data['transition']->getCondition()){
            $authoriseChangeState = $this->tcs->isChecked($data['transition']->getCondition());
        }

        if($authoriseChangeState){
            $state = $data['transition']->getEndState();

            $statusModif = new StatusModification();
            $statusModif->setApplication($application);
            $statusModif->setComment($data['comment']);
            $statusModif->setDateTime(new \DateTime());
            $statusModif->setState($state);

            $application = $this->tryTrigger($application, $state);

            $application->addStatusModification($statusModif);
            $application->setCurrentState($state->getMachineName());

            $this->em->persist($application);
            $this->em->flush();
            $message = $this->message->create(
                "Changement d'état de votre dossier pour la formation ".$application->getPromotion()->getCourse()->getName(),
                "uneadresse@hotmail.com",
                [$application->getStudent()->getEmail()],
                "AppBundle:email:studentChangeStateNotification.html.twig",
                array(
                    "formation" => $application->getPromotion()->getCourse()->getName(),
                    "stateStart" => $stateStart,
                    "stateEnd" => $application->getCurrentState(),
                )
            );
            $this->mailer->send($message);
            $this->session->getFlashBag()->add("success", "Email envoyé au destinataire");
        }
        else{
            $this->session->getFlashBag()->add("danger", $data['transition']->getCondition()->getErrorMessage());
        }
        return $application;
    }

    private function tryTrigger(Application $application, State $state) : Application {
        if($state->getTrigger()){
            $reflect = new ReflectionClass($state->getTrigger());
            switch (  $reflect->getShortName()) {
                case "CompanyTrigger":
                    $application->addDataAttachments(new Company());
                    break;
                case "EndTrigger":
                    throw new \DomainException("Trigger à implementer BIS");
                    break;
                default:
                    throw new \DomainException("Problème dans le choix du trigger");
            }
        }
        return $application;
    }
}