<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 09:05
 */

namespace AppBundle\Controller;

use AppBundle\Entity\AfterCourse;
use AppBundle\Entity\Company;
use AppBundle\Forms\Types\CompanyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StudentController extends Controller
{
    public function studentIndexAction(){

        $student = $this->getUser();
        $applications = $student->getApplications();

        return $this->render('AppBundle:Student:home.html.twig',[
            'applications' => $applications,
        ]);
    }

    /**
     * @ParamConverter("company", options={"mapping": {"companyId" : "id"}})
     */
    public function companyEditAction(Request $request, Company $company)
    {

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            $this->addFlash('success', 'Les informations de l\'entrprise ont bien été mise à jour.');

            return $this->redirectToRoute('student.home');
        }
        return $this->render('AppBundle:Student:company.html.twig',[
            'compagny' => $company,
            'form' => $form->createView()
        ]);
    }

    /**
     * @ParamConverter("afterCourse", options={"mapping": {"afterCourseId" : "id"}})
     */
    public function afterCourseEditAction(Request $request, AfterCourse $afterCourse)
    {

        $form = $this->createForm(AfterCourse::class, $afterCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($afterCourse);
            $em->flush();

            $this->addFlash('success', 'Votre situation après votre formation a bien été enregistrée.');

            return $this->redirectToRoute('student.home');
        }
        return $this->render('AppBundle:Student:company.html.twig',[
            'compagny' => $afterCourse,
            'form' => $form->createView()
        ]);
    }
}