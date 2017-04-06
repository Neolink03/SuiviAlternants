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
use Symfony\Component\HttpFoundation\RedirectResponse;



class StudentController extends Controller
{
    public function studentIndexAction(){

        $student = $this->getUser();
        $applications = $student->getApplications();

        return $this->render('AppBundle:Student:home.html.twig',[
            'applications' => $applications,
        ]);
    }

    public function displayPersonalInformationsAction(Request $request) {

        $student = $this->getUser();
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
                
                return new RedirectResponse($this->generateUrl("student.home"));
            }
            
            elseif ($form->isSubmitted() && !$form->isValid()){
                $this->addFlash(
                    'danger',
                    'Une ou plusieurs informations sont manquantes et/ou non valides    '
                );
            }
        }

        return $this->render('AppBundle:Student:personalInformations.html.twig',[
            'student' => $form->createView(),
        ]);
    }

}