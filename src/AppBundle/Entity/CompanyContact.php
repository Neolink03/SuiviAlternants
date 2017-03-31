<?php

namespace AppBundle\Entity;

/**
 * CompanyContact
 */
class CompanyContact
{
    private $id;
    private $companyName;
    private $firstNameContact;
    private $lastNameContact;
    private $email;
    private $phoneNumber;
    private $course;

    public function getId() : int
    {
        return $this->id;
    }

    public function setCompanyName(string $companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function setFirstNameContact(string $firstNameContact)
    {
        $this->firstNameContact = $firstNameContact;

        return $this;
    }

    public function getFirstNameContact()
    {
        return $this->firstNameContact;
    }

    public function setLastNameContact(string $lastNameContact)
    {
        $this->lastNameContact = $lastNameContact;

        return $this;
    }

    public function getLastNameContact()
    {
        return $this->lastNameContact;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function setCourse($course)
    {
        $this->course = $course;
    }


}

