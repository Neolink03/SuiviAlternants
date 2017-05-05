<?php

namespace AppBundle\Models\Dtos\Courses;

class Course {
    private $name;
    private $studentNumber;
    private $manager;
    private $coManager;
    private $secretariatContactDetails;
    
    function getName() {
        return $this->name;
    }

    function getStudentNumber() {
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

    function setName($name) {
        $this->name = $name;
    }

    function setStudentNumber($studentNumber) {
        $this->studentNumber = $studentNumber;
    }

    function setManager($managerSelector) {
        $this->manager = $managerSelector;
    }

    function setCoManager($coManagerSelector) {
        $this->coManager = $coManagerSelector;
    }

    function setSecretariatContactDetails($secretariatContactDetails) {
        $this->secretariatContactDetails = $secretariatContactDetails;
    }


}
