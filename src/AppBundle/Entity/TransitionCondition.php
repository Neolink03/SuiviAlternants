<?php

namespace AppBundle\Entity;

/**
 * TransitionCondition
 */
abstract class TransitionCondition
{
    private $id;
    private $transition;

    public function getId()
    {
        return $this->id;
    }

    public abstract function isChecked() : boolean;

    public function getTransition()
    {
        return $this->transition;
    }

    public function setTransition($transition)
    {
        $this->transition = $transition;
    }


}

