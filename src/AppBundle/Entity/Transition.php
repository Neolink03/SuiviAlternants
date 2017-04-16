<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;

class Transition
{
    /** @Exclude */
    private $id;
    /** @Exclude */
    private $workflow;
    private $name;
    /** @Exclude */
    private $machineName;
    /**
     * @SerializedName("startStateName")
     */
    private $startState;
    /** @SerializedName("endStateName") */
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

    public function getName()
    {
        return $this->name;
    }

    public function setMachineName(string $machineName)
    {
        $this->machineName = $machineName;

        return $this;
    }

    public function getMachineName()
    {
        return $this->machineName;
    }


    public function getStartState()
    {
        return $this->startState;
    }


    public function setStartState(State $startState)
    {
        $this->startState = $startState;
    }

    public function getEndState()
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

