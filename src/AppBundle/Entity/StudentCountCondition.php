<?php

namespace AppBundle\Entity;

/**
 * StudentCountTransition
 */
class StudentCountCondition extends TransitionCondition
{
    private $number;
    private $operator;

    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    public function getOperator()
    {
        return $this->operator;
    }


    public function isChecked(): boolean
    {
//        $actualNumber = 10;
//        eval('$result='.$actualNumber.' '.$this->getOperator().' '.$this->getNumber().';');
//
//        return $result;

        return false;
    }
}

