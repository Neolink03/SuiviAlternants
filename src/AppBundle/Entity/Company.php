<?php

namespace AppBundle\Entity;

/**
 * Company
 */
class Company extends DataAttachments
{
    private $name;
    private $address;
    private $employeeNumber;
    private $itNumber;

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getEmployeeNumber()
    {
        return $this->employeeNumber;
    }

    public function setEmployeeNumber($employeeNumber)
    {
        $this->employeeNumber = $employeeNumber;
    }

    public function getItNumber()
    {
        return $this->itNumber;
    }

    public function setItNumber($ItNumber)
    {
        $this->itNumber = $ItNumber;
    }
}

