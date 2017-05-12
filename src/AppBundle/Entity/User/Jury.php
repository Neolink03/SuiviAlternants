<?php

namespace AppBundle\Entity\User;

use AppBundle\Entity\Course;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

class Jury extends User
{
    protected $id;
    private $phoneNumber;
    private $coursesAttached;

    public function __construct()
    {
        parent::__construct();
        $this->coursesAttached = new ArrayCollection();
    }


    public function getId() : int
    {
        return $this->id;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getCoursesAttached()
    {
        return $this->coursesAttached;
    }

    public function setCoursesAttached($coursesAttached)
    {
        $this->coursesAttached = $coursesAttached;
    }

    public function addCoursesAttached(Course $course)
    {
        $this->coursesAttached[] = $course;

        return $this;
    }

}

