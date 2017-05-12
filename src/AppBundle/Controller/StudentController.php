<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 09:05
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Application;
use AppBundle\Entity\Company;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
    public function companyEditAction(Company $company)
    {
        return $this->render('AppBundle:Student:company.html.twig',[
            'compagny' => $company,
        ]);
    }
}