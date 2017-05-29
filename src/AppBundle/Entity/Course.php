<?php

namespace AppBundle\Entity;

use AppBundle\Entity\User\Jury;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Course
 */
class Course
{
    const DEFAULT_STUDENT_NUMBER = 25;
    
    private $id;
    private $name;
    private $studentNumber;
    private $manager;
    private $coManager;
    private $secretariatContactDetails;
    private $promotions;
    private $companyContacts;
    private $jurys;

    public function __construct()
    {
        $this->companyContacts = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->jurys = new ArrayCollection();
    }

    public function getPromotions()
    {
        return $this->promotions;
    }

    public function setPromotions($promotions)
    {
        $this->promotions = $promotions;

    }

    public function addPromotion(Promotion $promotion)
    {
        $this->promotions[] = $promotion;
        $promotion->setCourse($this);

        return $this;
    }

    public function removePromotion(Promotion $promotion)
    {
        $this->promotions->removeElement($promotion);
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setStudentNumber(int $studentNumber)
    {
        $this->studentNumber = $studentNumber;

        return $this;
    }

    public function getStudentNumber() : int
    {
        return $this->studentNumber;
    }
    
    function getManager() {
        return $this->manager;
    }

    function getCoManager() {
        return $this->coManager;
    }

    function getSecretariatContactDetails() {
        return $this->secretariatContactDetails;
    }

    function setManager($manager) {
        $this->manager = $manager;
    }

    function setCoManager($coManager) {
        $this->coManager = $coManager;
    }

    function setSecretariatContactDetails($secretariatContactDetails) {
        $this->secretariatContactDetails = $secretariatContactDetails;
    }

    public function getCompanyContacts()
    {
        return $this->companyContacts;
    }

    public function setCompanyContacts($companyContacts)
    {
        $this->companyContacts = $companyContacts;
    }

    public function addCompanyContacts(CompanyContact $companyContacts)
    {
        $this->companyContacts[] = $companyContacts;
        $companyContacts->setCourse($this);

        return $this;
    }

    public function removeCompanyContacts(CompanyContact $companyContacts)
    {
        $this->companyContacts->removeElement($companyContacts);
    }

    public function getJurys()
    {
        return $this->jurys;
    }

    public function setJurys($jurys)
    {
        $this->jurys = $jurys;
    }

    public function addJurys(Jury $jury)
    {
        $this->jurys[] = $jury;
        $jury->addCoursesAttached($this);

        return $this;
    }

    public function removeJurys(Jury $jury)
    {
        $this->$jury->removeElement($jury);
    }

}

