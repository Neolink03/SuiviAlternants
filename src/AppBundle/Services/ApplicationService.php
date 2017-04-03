<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services;


use AppBundle\Entity\Application;
use AppBundle\Entity\State;
use AppBundle\Entity\StatusModification;
use Doctrine\ORM\EntityManager;

class ApplicationService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setState(Application $application, array $data){
        $state = $this->getStateFromString($application, $data['transition']->getTos()[0]);

        $statusModif = new StatusModification();
        $statusModif->setApplication($application);
        $statusModif->setComment($data['comment']);
        $statusModif->setDateTime(new \DateTime());
        $statusModif->setState($state);

        $application->addStatusModification($statusModif);
        $application->setCurrentState($state->getMachineName());


        $this->em->persist($application);
        $this->em->flush();

        return $application;
    }

    private function getStateFromString(Application $application, string $stateString){
        $state = $this->em->getRepository(State::class)->findOneBy(
            array(
                'workflow' => $application->getPromotion()->getCourse()->getWorkflow(),
                'machineName' => $stateString
                )
        );
        return $state;
    }
}