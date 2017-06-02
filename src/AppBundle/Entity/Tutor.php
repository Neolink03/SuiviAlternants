<?php

namespace AppBundle\Entity;

/**
 * Tutor
 */
class Tutor extends DataAttachments
{
    private $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

}

