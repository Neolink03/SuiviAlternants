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
use Symfony\Component\HttpFoundation\RedirectResponse;

use AppBundle\Forms\Types\Courses\CourseCreateType;

class AdminController extends Controller
{
    public function courseListAction(Request $request){
        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();
        return $this->render('AppBundle:Admin:courseList.html.twig', [
            "courses" => $courses,
        ]);
    }
    
    public function createCourseAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CourseCreateType::class);
        
        if ($request->isMethod('post')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                
                $courseFormData = $form->getData();
                $course = new Course();
                $course->setName($courseFormData['name']);
                $course->setManager($courseFormData['manager']['selector']);
                $course->setCoManager($courseFormData['coManager']['selector']);
                $course->setSecretariatContactDetails($courseFormData['secretariatContactDetails']);
                $course->setStudentNumber(25);
                
                $em->persist($course);
                $em->flush($course);
                
                return new RedirectResponse($this->generateUrl("admin.course_create_success"));
            }
        }
        
        return $this->render('AppBundle:Admin:editCourse.html.twig', [
            "courses" => null,
            "currentForm" => $form->createView()
        ]);
    }
    
    public function createCourseSuccessAction(Request $request) {
        return new \Symfony\Component\HttpFoundation\Response("Course successfully created");
    }

    public function dumpWorkflowAction(Request $request){
        $this->get('app.factory.workflow')->generateWorflowFromTrainging(12, 2018);
    }
}