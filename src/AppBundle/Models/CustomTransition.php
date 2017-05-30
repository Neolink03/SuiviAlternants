<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Models;


class CustomTransition
{
    private $name;
    private $froms;
    private $tos;

    /**
     * @param string          $name
     * @param string|string[] $froms
     * @param string|string[] $tos
     */
    public function __construct($name, $froms, $tos)
    {
        $this->name = $name;
        $this->froms = (array) $froms;
        $this->tos = (array) $tos;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getFroms()
    {
        return $this->froms;
    }

    public function getTos()
    {
        return $this->tos;
    }
}