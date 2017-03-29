<?php

/**
 * Created by PhpStorm.
 * User: lp
 * Date: 06/03/2017
 * Time: 16:28
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Forms\Types\Courses\CourseType;

class AdminController extends Controller
{
    public function courseListAction(Request $request){
        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();
        return $this->render('AppBundle:Admin:courseList.html.twig', [
            "courses" => $courses,
        ]);
    }
    
    public function editCourseAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $courseManagers = $em->getRepository(\AppBundle\Entity\User\CourseManager::class)
                             ->findAll();
        
        return $this->render('AppBundle:Admin:editCourse.html.twig', [
            "courses" => null,
            "currentForm" => $this->createForm(CourseType::class)->createView()
        ]);
    }

    public function dumpWorkflowAction(Request $request){
        $this->get('app.factory.workflow')->generateWorflowFromTrainging(12, 2018);
    }
}