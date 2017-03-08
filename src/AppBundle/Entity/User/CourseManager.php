<?php

namespace AppBundle\Entity\User;

use AppBundle\Entity\User;

class CourseManager extends User
{
    private $id;
    private $phoneNumber;

    public function getId() : int
    {
        return $this->id;
    }

    public function setPhoneNumber(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPhoneNumber() : string
    {
        return $this->phoneNumber;
    }
}

