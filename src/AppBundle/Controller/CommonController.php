<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 31/03/2017
 * Time: 11:07
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommonController extends Controller
{
    public function indexAction(Request $request){
        $user = $this->getUser();
        if ($user->hasRole('ROLE_ADMIN')){
            return $this->redirectToRoute('admin.home');
        }
        if ($user->hasRole('ROLE_MANAGER')){
            return $this->redirectToRoute('courseManager.home');
        }
        if ($user->hasRole('ROLE_STUDENT')){
            return $this->redirectToRoute('student.home');
        }
    }
}