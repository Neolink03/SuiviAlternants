<?php

namespace AppBundle\Entity\User;

use AppBundle\Entity\User;

class Jury extends User
{
    protected $id;

    public function getId() : int
    {
        return $this->id;
    }
}

