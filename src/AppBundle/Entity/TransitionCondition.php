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

    public function getTransition()
    {
        return $this->transition;
    }

    public function setTransition($transition)
    {
        $this->transition = $transition;
    }

    public function getErrorMessage(): string
    {
        return "Impossible de passer à cet état, la condition n'est pas respectée";
    }
}

