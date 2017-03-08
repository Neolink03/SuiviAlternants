<?php

namespace AppBundle\Entity;

class Application
{
    private $id;
    private $year;

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
}

