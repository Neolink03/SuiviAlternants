<?php

namespace AppBundle\Entity;

/**
 * AfterCourse
 */
class AfterCourse extends DataAttachments
{
    private $job;
    private $wage;
    private $company;
    private $comment;

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

