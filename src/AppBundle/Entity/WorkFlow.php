<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * WorkFlow
 */
class WorkFlow
{
    private $id;
    private $promotion;
    private $states;
    private $transitions;

    public function __construct()
    {
        $this->states = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPromotion()
    {
        return $this->promotion;
    }

    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;
    }

    public function getStates()
    {
        return $this->states;
    }

    public function setStates($states)
    {
        $this->states = $states;
    }

    public function addState(State $state)
    {
        $this->states[] = $state;
        $state->setWorkflow($this);

        return $this;
    }

    public function getFirstState(){
        return $this->states->first();
    }

    public function removeState(State $state)
    {
        $this->states->removeElement($state);
    }

    public function getTransitions()
    {
        return $this->transitions;
    }

    public function setTransitions($transitions)
    {
        $this->transitions = $transitions;
    }

    public function addTransition(Transition $transition)
    {
        $this->transitions[] = $transition;
        $transition->setWorkflow($this);

        return $this;
    }

    public function removeTransition(Transition $transition)
    {
        $this->transitions->removeElement($transition);
    }
}

