<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 09:57
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Promotion;
use AppBundle\Entity\User\Student;
use AppBundle\Forms\Types\PromotionType;
use AppBundle\Forms\Types\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CourseManagerController extends Controller
{
    public function addStudentAction(Request $request)
    {
        $student = new Student();
        $studentForm = $this->createForm(UserType::class, $student);

        if ($request->isMethod('post')) {

            $studentForm->handleRequest($request);

            if ($studentForm->isSubmitted() && $studentForm->isValid()) {
                dump($studentForm);
                die();
                /* TO DO
                $newStudent = $studentForm->getData();

                $this->get('sign_up')->signUpCustomer($newStudent);

                $customer = $this->get('repositories.customers')
                    ->loadUserByUsername($signUp->email)
                ;

                return new RedirectResponse('/');
                */
            }
        }

        return $this->render('AppBundle:CourseManager:createStudent.html.twig', [
            'student' => $studentForm->createView(),
        ]);
    }

    public function editCourseAction(Request $request)
    {
        $promotion = new Promotion();
        $promotionForm = $this->createForm(PromotionType::class, $promotion);

        if ($request->isMethod('post')) {

            $promotionForm->handleRequest($request);

            if ($promotionForm->isSubmitted() && $promotionForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($promotion);
                $em->flush();
            }
        }

        return $this->render('AppBundle:CourseManager:editCourse.html.twig', [
            'promotionForm' => $promotionForm->createView()
        ]);
    }

}