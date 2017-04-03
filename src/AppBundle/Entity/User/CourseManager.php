<?php

namespace AppBundle\Entity\User;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

class CourseManager extends User
{
    protected $id;
    private $phoneNumber;
    private $courseManaged;
    private $courseCoManaged;

    public function __construct()
    {
        parent::__construct();
        $this->courseManaged = new ArrayCollection();
        $this->courseCoManaged = new ArrayCollection();
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function setPhoneNumber(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPhoneNumber() : string
    {
        return $this->phoneNumber;
    }

    public function getCourseManaged()
    {
        return $this->courseManaged;
    }

    public function setCourseManaged($courseManaged)
    {
        $this->courseManaged = $courseManaged;
    }

    public function getCourseCoManaged()
    {
        return $this->courseCoManaged;
    }

    public function setCourseCoManaged($courseCoManaged)
    {
        $this->courseCoManaged = $courseCoManaged;
    }

}

