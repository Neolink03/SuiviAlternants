<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation\Exclude;

/**
 * State
 */
class State
{
    /** @Exclude */
    private $id;
    private $name;
    /** @Exclude */
    private $machineName;
    /** @Exclude */
    private $workflow;

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setMachineName($machineName)
    {
        $this->machineName = $machineName;

        return $this;
    }

    public function getMachineName()
    {
        return $this->machineName;
    }

    public function getWorkflow() : WorkFlow
    {
        return $this->workflow;
    }

    public function setWorkflow(WorkFlow $workflow)
    {
        $this->workflow = $workflow;
    }

}

