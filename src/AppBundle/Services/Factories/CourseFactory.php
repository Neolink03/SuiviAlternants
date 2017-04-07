<?php

namespace AppBundle\Services\Factories;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Course;
use AppBundle\Models\Dtos\Courses\Course as CourseDto;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class CourseFactory {
    
    private $em;
    private $session;

    public function __construct(EntityManager $em, Session $session)
    {
        $this->em = $em;
        $this->session = $session;
    }
    
    public function saveNewCourse(CourseDto $courseDto) {

        $courseDataBase = $this->em->getRepository(Course::class)->findOneBy(array(
            'name' => $courseDto->getName()
        ));

        $course = new Course();
        $course->setName($courseDto->getName());
        $course->setManager($courseDto->getManager());
        $course->setCoManager($courseDto->getCoManager());
        $course->setSecretariatContactDetails($courseDto->getSecretariatContactDetails());
        $course->setStudentNumber(Course::DEFAULT_STUDENT_NUMBER); // no student number field in course create form 


        if(($course->getManager() != $course->getCoManager()) && is_null($courseDataBase)){
            $this->em->persist($course);
            $this->em->flush($course);
            return true;
        }else{
            if($course->getManager() == $course->getCoManager())
                $this->session->getFlashBag()->add("danger", "Le manager et le co-manager ne peuvent pas être la même personne.");
            elseif(!is_null($courseDataBase))
                $this->session->getFlashBag()->add("danger", "La formation existe déjà.");
            return false;
        }
    }
}
