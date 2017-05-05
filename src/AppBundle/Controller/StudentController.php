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


}