<?php

namespace AppBundle\Entity;

/**
 * Course
 */
class Course
{
    private $id;
    private $name;
    private $studentNumber;
    private $manager;
    private $coManager;
    private $secretariatContactDetails;
    
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

    public function setStudentNumber(int $studentNumber)
    {
        $this->studentNumber = $studentNumber;

        return $this;
    }

    public function getStudentNumber() : int
    {
        return $this->studentNumber;
    }
    
    function getManager() {
        return $this->manager;
    }

    function getCoManager() {
        return $this->coManager;
    }

    function getSecretariatContactDetails() {
        return $this->secretariatContactDetails;
    }

    function setManager($manager) {
        $this->manager = $manager;
    }

    function setCoManager($coManager) {
        $this->coManager = $coManager;
    }

    function setSecretariatContactDetails($secretariatContactDetails) {
        $this->secretariatContactDetails = $secretariatContactDetails;
    }
}

