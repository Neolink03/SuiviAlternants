<?php

namespace AppBundle\Entity;

/**
 * DataAttachments
 */
abstract class DataAttachments
{
    private $id;
    private $application;

    public function getId()
    {
        return $this->id;
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function setApplication(Application $application)
    {
        $this->application = $application;
    }


}

