<?php

namespace AppBundle\Entity\User;

use AppBundle\Entity\Application;
use AppBundle\Entity\User;
use AppBundle\Forms\Types\AddressType;
use Doctrine\Common\Collections\ArrayCollection;

class Student extends User
{
    protected $id;
    private $address;
    private $phone;
    private $cellphone;
    private $birthday;
    private $applications;

    public function __construct()
    {
        parent::__construct();
        $this->applications = new ArrayCollection();
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getCellphone()
    {
        return $this->cellphone;
    }

    public function getBirthday()
    {
        return $this->birthday;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function setCellphone($cellphone)
    {
        $this->cellphone = $cellphone;
    }

    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    public function getApplications()
    {
        return $this->applications;
    }

    public function setTransitions($application)
    {
        $this->applications = $application;
    }

    public function addTransition(Application $application)
    {
        $this->applications[] = $application;
        $application->setStudent($this);

        return $this;
    }

    public function removeTransition(Application $application)
    {
        $this->applications->removeElement($application);
    }
}

