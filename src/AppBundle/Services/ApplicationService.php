<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services;


use AppBundle\Entity\AfterCourse;
use AppBundle\Entity\Application;
use AppBundle\Entity\Company;
use AppBundle\Entity\State;
use AppBundle\Entity\StatusModification;
use AppBundle\Entity\Tutor;
use AppBundle\Services\Factories\SwiftMessageFactory;
use Doctrine\ORM\EntityManager;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Session\Session;

class ApplicationService
{
    private $em;
    private $tcs;
    private $session;
    private $messageFactory;
    private $mailer;

    public function __construct(
        EntityManager $em,
        TransitionConditionService $tcs,
        Session $session,
        SwiftMessageFactory $messageFactory,
        \Swift_Mailer $mailer
    )
    {
        $this->em = $em;
        $this->tcs = $tcs;
        $this->session = $session;
        $this->messageFactory = $messageFactory;
        $this->mailer = $mailer;
    }

    public function setState(Application $application, array $data){
        $authoriseChangeState =true;
        $stateStart = $data['transition']->getStartState();

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

            $this->sendMailIfAllow($application, $stateStart, $state);
        }
        else{
            $this->session->getFlashBag()->add("danger", $data['transition']->getCondition()->getErrorMessage());
        }
        return $application;
    }

    private function sendMailIfAllow(Application $application, State $stateStart, State $stateEnd){
        if($stateEnd->getSendMail()){
            $message = $this->messageFactory->create(
                "Changement d'état de votre dossier pour la formation ".$application->getPromotion()->getCourse()->getName(),
                "no-reply@univ-lyon1.fr",
                [$application->getStudent()->getEmail()],
                "AppBundle:email:studentChangeStateNotification.html.twig",
                array(
                    "formation" => $application->getPromotion()->getCourse()->getName(),
                    "stateStart" => $stateStart->getName(),
                    "stateEnd" => $stateEnd->getName(),
                )
            );
            $this->mailer->send($message);
            $this->session->getFlashBag()->add("success", "Email envoyé à l'étudiant");
        }
        else{
            $this->session->getFlashBag()->add("success", "Changement réussi, aucun email envoyé");
        }

    }

    private function tryTrigger(Application $application, State $state) : Application {
        if($state->getTrigger()){
            $reflect = new ReflectionClass($state->getTrigger());
            switch (  $reflect->getShortName()) {
                case "CompanyTrigger":
                    $application->addDataAttachments(new Company());
                    break;
                case "AfterCourseTrigger":
                    $application->addDataAttachments(new AfterCourse());
                    break;
                case "TutorTrigger":
                    $application->addDataAttachments(new Tutor());
                    break;
                default:
                    throw new \DomainException("Problème dans le choix du trigger");
            }
        }
        return $application;
    }
}