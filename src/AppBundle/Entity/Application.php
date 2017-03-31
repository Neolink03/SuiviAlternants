<?php

namespace AppBundle\Entity;

class Application
{
    private $id;
    private $year;
    private $student;
    private $promotion;

    public function getId() : int
    {
        return $this->id;
    }

    public function setYear(\DateTime $year)
    {
        $this->year = $year;

        return $this;
    }

    public function getYear() : \DateTime
    {
        return $this->year;
    }

    public function getStudent()
    {
        return $this->student;
    }

    public function setStudent($student)
    {
        $this->student = $student;
    }

    public function getPromotion()
    {
        return $this->promotion;
    }

    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;
    }

}

