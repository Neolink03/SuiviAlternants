<?php

namespace AppBundle\Entity;

/**
 * Company
 */
class Company extends DataAttachments
{
    private $name;

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
}

