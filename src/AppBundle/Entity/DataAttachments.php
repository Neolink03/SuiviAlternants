<?php

namespace AppBundle\Entity;

/**
 * DataAttachments
 */
abstract class DataAttachments
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}

