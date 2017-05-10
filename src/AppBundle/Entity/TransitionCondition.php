<?php

namespace AppBundle\Entity;

/**
 * TransitionCondition
 */
abstract class TransitionCondition
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}

