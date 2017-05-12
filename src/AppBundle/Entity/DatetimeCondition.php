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

    public function isChecked(): boolean
    {
        return false;
    }
}

