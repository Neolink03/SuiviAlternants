<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services;


use AppBundle\Entity\Application;
use AppBundle\Entity\StudentCountCondition;
use Doctrine\ORM\EntityManager;

class TransitionConditionService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function isChecked(StudentCountCondition $studentCondition)
    {
        $actualNumberOfState = count($this->em
            ->getRepository(Application::class)
            ->findByCurrentState($studentCondition->getTransition()->getEndState()->getMachineName()));

        eval('$result='.$actualNumberOfState.' '.$studentCondition->getOperator().' '.$studentCondition->getNumber().';');
        return $result;

    }
}