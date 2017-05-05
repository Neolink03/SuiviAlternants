<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 05/05/2017
 * Time: 16:03
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class JuryController extends Controller
{
    public function juryIndexAction(Request $request){

        return $this->render('AppBundle:Jury:home.html.twig');
    }
}