<?php

namespace AppBundle\Errors\Student;

use AppBundle\Entity\User\Student;
use AppBundle\Entity\Promotion;

class StudentAlreadyHasApplicationException extends \Exception {

    public function __construct(Student $student, Promotion $promotion)
    {
        parent::__construct(
            sprintf(
              "Cannot add student %s to promotion n°%s, it already have an application",
              $student->getId(), $promotion->getId())
        );
    }
}
