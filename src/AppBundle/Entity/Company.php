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
    private $tutorFirstame;
    private $tutorLastname;
    private $tutorEmail;
    private $tutorPhonenumber;

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

    public function getTutorFirstame()
    {
        return $this->tutorFirstame;
    }

    public function setTutorFirstame($tutorFirstame)
    {
        $this->tutorFirstame = $tutorFirstame;
    }

    public function getTutorLastname()
    {
        return $this->tutorLastname;
    }

    public function setTutorLastname($tutorLastname)
    {
        $this->tutorLastname = $tutorLastname;
    }

    public function getTutorEmail()
    {
        return $this->tutorEmail;
    }

    public function setTutorEmail($tutorEmail)
    {
        $this->tutorEmail = $tutorEmail;
    }

    public function getTutorPhonenumber()
    {
        return $this->tutorPhonenumber;
    }

    public function setTutorPhonenumber($tutorPhonenumber)
    {
        $this->tutorPhonenumber = $tutorPhonenumber;
    }
}

