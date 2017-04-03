<?php

/**
 * Created by PhpStorm.
 * User: lp
 * Date: 06/03/2017
 * Time: 16:28
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use AppBundle\Forms\Types\AdminNewUserType;
use AppBundle\Models\AdminNewUserDto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use AppBundle\Forms\Types\Courses\CourseCreateType;
use AppBundle\Models\Dtos\Courses\Course as CourseDto;

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
        $form = $this->createForm(CourseCreateType::class, new CourseDto());
        
        if ($request->isMethod('post')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                
                $courseDto = $form->getData();
                $this->get('app.factory.course')->saveNewCourse($courseDto);
                $this->addFlash("success", "La formation a bien été créée.");
                
                return new RedirectResponse($this->generateUrl("admin.home"));
            }
        }
        
        return $this->render('AppBundle:Admin:createCourse.html.twig', [
            "courses" => null,
            "courseForm" => $form->createView()
        ]);
    }

    public function dumpWorkflowAction(Request $request){
        $this->get('app.factory.workflow')->generateWorflowFromTrainging(12, 2018);
    }

    public function userAddAction(Request $request)
    {
        $adminNewUserDto = new AdminNewUserDto();

        $form = $this->createForm(AdminNewUserType::class,$adminNewUserDto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.factory.user')->saveFromAdmin($adminNewUserDto);
            //return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Admin:addUser.html.twig', [
            'form' => $form->createView()
        ]);
    }


}