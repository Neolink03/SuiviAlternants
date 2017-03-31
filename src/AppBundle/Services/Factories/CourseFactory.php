<?php

namespace AppBundle\Services\Factories;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Course;
use AppBundle\Models\Dtos\Courses\Course as CourseDto;

class CourseFactory {
    
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function saveFromAdmin(CourseDto $courseDto) {
        $course = new Course();
        $course->setName($courseDto->getName());
        $course->setManager($courseDto->getManager());
        $course->setCoManager($courseDto->getCoManager());
        $course->setSecretariatContactDetails($courseDto->getSecretariatContactDetails());
        $course->setStudentNumber(Course::DEFAULT_STUDENT_NUMBER); // no student number field in course create form 

        $this->em->persist($course);
        $this->em->flush($course);
    }
}
