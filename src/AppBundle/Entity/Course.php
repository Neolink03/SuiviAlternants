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

    public function __construct($name, $studentNumber)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->studentNumber = $studentNumber;
    }

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

    public function setStudentNumber($studentNumber)
    {
        $this->studentNumber = $studentNumber;

        return $this;
    }

    public function getStudentNumber()
    {
        return $this->studentNumber;
    }
}

