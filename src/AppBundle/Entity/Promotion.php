<?php

namespace AppBundle\Entity;

/**
 * Promotion
 */
class Promotion
{
    private $id;
    private $name;
    private $startDate;
    private $endDate;
    private $studentNumber;
    private $course;
    private $applications;

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

    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setStudentNumber($studentNumber)
    {
        $this->studentNumber = $studentNumber;

        return $this;
    }

    public function getStudentNumber()
    {
        return $this->studentNumber;
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function setCourse($course)
    {
        $this->course = $course;
    }

    public function getApplications()
    {
        return $this->applications;
    }

    public function setApplication($application)
    {
        $this->applications = $application;
    }

    public function addApplication(Application $application)
    {
        $this->applications[] = $application;
        $application->setPromotion($this);

        return $this;
    }

    public function removeApplication(Application $application)
    {
        $this->applications->removeElement($application);
    }
}

