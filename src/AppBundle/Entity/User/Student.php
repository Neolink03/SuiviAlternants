<?php

namespace AppBundle\Entity\User;

use AppBundle\Entity\User;

class Student extends User
{
    private $id;

    public function getId() : int
    {
        return $this->id;
    }
}

