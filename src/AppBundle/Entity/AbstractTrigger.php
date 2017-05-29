<?php

namespace AppBundle\Entity;

/**
 * AbstractTrigger
 */
abstract class AbstractTrigger
{
    /**
     * @var int
     */
    private $id;

    private $state;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public abstract function trigger();
}

