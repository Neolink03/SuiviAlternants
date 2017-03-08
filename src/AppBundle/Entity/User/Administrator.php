<?php

namespace AppBundle\Entity\User;

use AppBundle\Entity\User;

class Administrator extends User
{
    public function getId() : int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

}

