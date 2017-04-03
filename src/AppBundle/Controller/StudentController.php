<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 09:05
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User\Student;
use AppBundle\Forms\Types\StudentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class StudentController extends Controller
{
    /**
     * @ParamConverter("student", options={"mapping": {"studentId" : "id"}})
     */
    public function studentIndexAction(Student $student){

        $applications = $student->getApplications();

        return $this->render('AppBundle:Student:home.html.twig',[
            'applications' => $applications,
        ]);
    }

    /**
     * @ParamConverter("student", options={"mapping": {"studentId" : "id"}})
     */
    public function displayPersonalInformationsAction(Student $student, Request $request){

        $form = $this->createForm(StudentType::class, $student);

        if ($request->isMethod('post')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($student);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Vos informations ont bien été mises a jour!'
                );
            }
        }

        return $this->render('AppBundle:Student:personalInformations.html.twig',[
            'student' => $form->createView(),
        ]);
    }

}