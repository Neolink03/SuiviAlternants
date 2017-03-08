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

    public function getId() : int
    {
        return $this->id;
    }

    public function setCompanyName(string $companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCompanyName() : string
    {
        return $this->companyName;
    }

    public function setFirstNameContact(string $firstNameContact)
    {
        $this->firstNameContact = $firstNameContact;

        return $this;
    }

    public function getFirstNameContact() : string
    {
        return $this->firstNameContact;
    }

    public function setLastNameContact(string $lastNameContact)
    {
        $this->lastNameContact = $lastNameContact;

        return $this;
    }

    public function getLastNameContact() : string
    {
        return $this->lastNameContact;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getPhoneNumber() : string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }
}

