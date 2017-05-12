<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services;


use AppBundle\Entity\Application;
use AppBundle\Entity\DatetimeCondition;
use AppBundle\Entity\StudentCountCondition;
use AppBundle\Entity\TransitionCondition;
use DateTime;
use Doctrine\ORM\EntityManager;

class TransitionConditionService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function isChecked(TransitionCondition $condition)
    {
        if ($condition instanceof StudentCountCondition) {
           return $this->checkStudentCountCondition($condition);
        }
        if ($condition instanceof DatetimeCondition) {
            return $this->checkDatetimeCondition($condition);
        }
        return false;
    }

    public function checkStudentCountCondition(StudentCountCondition $studentCondition)
    {
        $actualNumberOfState = count($this->em
            ->getRepository(Application::class)
            ->findByCurrentState($studentCondition->getTransition()->getEndState()->getMachineName()));

        eval('$result='.$actualNumberOfState.' '.$studentCondition->getOperator().' '.$studentCondition->getNumber().';');
        return $result;
    }

    public function checkDatetimeCondition(DatetimeCondition $datetimeCondition)
    {
        eval('$result= new DateTime() '.$datetimeCondition->getOperator().' $datetimeCondition->getDatetime();');
        return $result;
    }
}