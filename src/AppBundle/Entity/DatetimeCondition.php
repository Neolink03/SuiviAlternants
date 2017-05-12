<?php

namespace AppBundle\Entity;

/**
 * DatetimeCondition
 */
class DatetimeCondition extends TransitionCondition
{
    private $datetime;
    private $operator;

    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getDatetime()
    {
        return $this->datetime;
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

    public function getErrorMessage(): string
    {
        return "Impossible de passer à cet état, la changement doit être fait ". $this->getOperator()." ".$this->getDatetime()->format('Y-m-d H:i:s').".";
    }
}

