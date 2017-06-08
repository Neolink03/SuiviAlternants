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
    private $trigger;
    /** @Exclude */
    private $workflow;
    private $juryCanEdit;
    private $sendMail;
    private $isVisibleByStudent;

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

    public function getTrigger()
    {
        return $this->trigger;
    }

    public function setTrigger($trigger)
    {
        $this->trigger = $trigger;
    }

    public function getJuryCanEdit()
    {
        return $this->juryCanEdit;
    }

    public function setJuryCanEdit($juryCanEdit)
    {
        $this->juryCanEdit = $juryCanEdit;
    }

    public function getSendMail()
    {
        return $this->sendMail;
    }

    public function setSendMail($sendMail)
    {
        $this->sendMail = $sendMail;
    }

    public function getIsVisibleByStudent()
    {
        return $this->isVisibleByStudent;
    }

    public function setIsVisibleByStudent($isVisibleByStudent)
    {
        $this->isVisibleByStudent = $isVisibleByStudent;
    }


}

