<?php

namespace AppBundle\Entity\User;

use AppBundle\Entity\User;

class Student extends User
{
    protected $id;

    public function getId() : int
    {
        return $this->id;
    }
}

