<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Application
{
    private $id;
    private $student;
    private $promotion;
    private $statusModifications;
    private $currentState = "";
    private $dataAttachments;

    public function __construct()
    {
        $this->dataAttachments = new ArrayCollection();
    }

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
        return $this->statusModifications->first();
    }

    public function getCurrentState()
    {
        return $this->currentState;
    }

    public function setCurrentState($currentState)
    {
        $this->currentState = $currentState;
    }

    public function getDataAttachments()
    {
        return $this->dataAttachments;
    }

    public function setDataAttachments($dataAttachments)
    {
        $this->dataAttachments = $dataAttachments;
    }

    public function addDataAttachments(DataAttachments $dataAttachment)
    {
        $insert = true;
        foreach ($this->dataAttachments as $stockedDataAttachment){
            if($dataAttachment instanceof $stockedDataAttachment){
                $insert = false;
            }
        }

        if($insert){
            $this->dataAttachments[] = $dataAttachment;
            $dataAttachment->setApplication($this);
        }

        return $this;
    }

    public function removeDataAttachments(DataAttachments $dataAttachments)
    {
        $this->dataAttachments->removeElement($dataAttachments);
    }
}

