<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 09:57
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Course;
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
        $em = $this->getDoctrine()->getManager();

        $course = $em->getRepository(Course::class)->find($request->get('courseId'));
        $promotion = new Promotion();
        $promotion->setCourse($course);

        $promotionForm = $this->createForm(PromotionType::class, $promotion);

        if ($request->isMethod('post')) {

            $promotionForm->handleRequest($request);

            if ($promotionForm->isSubmitted() && $promotionForm->isValid()) {
                $em->persist($promotion);
                $em->flush();
                $this->addFlash('success', 'La promotion a été ajoutée avec succès.');
            }
        }

        return $this->render('AppBundle:CourseManager:editCourse.html.twig', [
            'promotionForm' => $promotionForm->createView()
        ]);
    }

    public function studentListAction(Request $request) {
        $students = $this->getDoctrine()->getManagerForClass(Student::class)
                                        ->getRepository(Student::class)
                                        ->findAll();
        
        return $this->render('AppBundle:Student:list.html.twig', [
            "students" => $students
        ]);
    }
}