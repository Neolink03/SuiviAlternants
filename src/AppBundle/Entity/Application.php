<?php

namespace AppBundle\Entity;

class Application
{
    private $id;
    private $student;
    private $promotion;
    private $statusModifications;
    private $currentState = "";

    public function getId() : int
    {
        return $this->id;
    }

    public function getStudent()
    {
        return $this->student;
    }

    public function setStudent($student)
    {
        $this->student = $student;
    }

    public function getPromotion(): Promotion
    {
        return $this->promotion;
    }

    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;
    }

    public function getStatusModifications()
    {
        return $this->statusModifications;
    }

    public function setStatusModification(State $statusModifications)
    {
        $this->statusModifications = $statusModifications;
    }

    public function addStatusModification(StatusModification $statusModifications)
    {
        $this->statusModifications[] = $statusModifications;
        $statusModifications->setApplication($this);

        return $this;
    }

    public function removeStatusModification(StatusModification $statusModifications)
    {
        $this->statusModifications->removeElement($statusModifications);
    }

    public function getLastStatusModification(){
        return $this->statusModifications->last();
    }

    public function getCurrentState()
    {
        return $this->currentState;
    }

    public function setCurrentState($currentState)
    {
        $this->currentState = $currentState;
    }
}

