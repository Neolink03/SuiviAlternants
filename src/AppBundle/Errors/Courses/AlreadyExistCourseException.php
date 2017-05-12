<?php

namespace AppBundle\Errors\Courses;

use AppBundle\Entity\Course;

class AlreadyExistCourseException extends \Exception {
    
    public function __construct(Course $course)
    {
        parent::__construct(
            sprintf(
                "Course %s with id = %s already exist", 
                $course->getName(), $course->getId()
            )
        );
    }
}
