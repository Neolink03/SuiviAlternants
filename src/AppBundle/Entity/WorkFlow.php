<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Exclude;

/**
 * WorkFlow
 */
class WorkFlow
{
    /** @Exclude */
    private $id;
    /** @Exclude */
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
    
    public function renameState(string $name) {
        
        foreach ($this->getStates()->toArray() as $state) {
            if ($state->getName() == $name) {
                $state->setName($name);
                $state->setMachineName($name);
            }
        }
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
    
    public function renameTransition(string $name) {
        
        foreach ($this->getTransitions()->toArray() as $transition) {
            if ($transition->getName() == $name) {
                $transition->setName($name);
                $transition->setMachineName($name);
            }
        }
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

