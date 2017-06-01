<?php

namespace AppBundle\Entity;

/**
 * AfterCourse
 */
class AfterCourse
{
    private $id;
    private $job;
    private $wage;
    private $company;
    private $comment;

    public function getId()
    {
        return $this->id;
    }

    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    public function getJob()
    {
        return $this->job;
    }

    public function setWage($wage)
    {
        $this->wage = $wage;

        return $this;
    }

    public function getWage()
    {
        return $this->wage;
    }

    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    public function getCompany()
    {
        return $this->company;
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
}

