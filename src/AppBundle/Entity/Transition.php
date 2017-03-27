<?php

namespace AppBundle\Entity;

class Transition
{
    private $id;
    private $workflow;
    private $name;
    private $machineName;
    private $startState;
    private $endState;


    public function getId()
    {
        return $this->id;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setMachineName(string $machineName)
    {
        $this->machineName = $machineName;

        return $this;
    }

    public function getMachineName() : string
    {
        return $this->machineName;
    }


    public function getStartState() : State
    {
        return $this->startState;
    }


    public function setStartState(State $startState)
    {
        $this->startState = $startState;
    }

    public function getEndState() : State
    {
        return $this->endState;
    }

    public function setEndState(State $endState)
    {
        $this->endState = $endState;
    }

    public function getWorkflow()
    {
        return $this->workflow;
    }

    public function setWorkflow($workflow)
    {
        $this->workflow = $workflow;
    }

}

