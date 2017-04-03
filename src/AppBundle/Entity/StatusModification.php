<?php

namespace AppBundle\Entity;

/**
 * StatusModification
 */
class StatusModification
{
    private $id;
    private $dateTime;
    private $comment;
    private $application;
    private $state;

    public function getId()
    {
        return $this->id;
    }

    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getDateTime()
    {
        return $this->dateTime;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function setApplication($application)
    {
        $this->application = $application;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }
}

