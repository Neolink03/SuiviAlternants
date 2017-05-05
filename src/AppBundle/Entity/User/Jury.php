<?php

namespace AppBundle\Entity\User;

use AppBundle\Entity\User;

class Jury extends User
{
    protected $id;
    private $phoneNumber;

    public function getId() : int
    {
        return $this->id;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }


}

