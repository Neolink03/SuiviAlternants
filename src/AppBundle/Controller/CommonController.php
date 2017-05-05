<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 31/03/2017
 * Time: 11:07
 */

namespace AppBundle\Controller;

use AppBundle\Forms\Types\ChangePasswordType;
use AppBundle\Forms\Types\CourseManagerType;
use AppBundle\Forms\Types\StudentType;
use AppBundle\Forms\Types\UserType;
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
        if ($user->hasRole('ROLE_JURY')){
            return $this->redirectToRoute('jury.home');
        }
        if ($user->hasRole('ROLE_STUDENT')){
            return $this->redirectToRoute('student.home');
        }
    }

    public function displayPersonalInformationsAction(Request $request)
    {
        $user = $this->getUser();

        if ($user->hasRole('ROLE_ADMIN'))
            $form = $this->createForm(UserType::class, $user, ['isDisabled' => true]);

        if ($user->hasRole('ROLE_MANAGER') || $user->hasRole('ROLE_JURY'))
            $form = $this->createForm(CourseManagerType::class, $user, ['isDisabled' => true]);

        if ($user->hasRole('ROLE_STUDENT'))
            $form = $this->createForm(StudentType::class, $user, ['isDisabled' => true]);

        if ($request->isMethod('post')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Vos informations ont bien été mises a jour!'
                );
            } elseif ($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash(
                    'danger',
                    'Une ou plusieurs informations sont manquantes et/ou non valides    '
                );
            }
        }

        if ($user->hasRole('ROLE_ADMIN')) {
            return $this->render('AppBundle:Admin:personalInformations.html.twig', [
                'administrator' => $form->createView(),
            ]);
        }
        if ($user->hasRole('ROLE_MANAGER')) {
            return $this->render('AppBundle:CourseManager:personalInformations.html.twig', [
                'courseManager' => $form->createView(),
            ]);
        }
        if ($user->hasRole('ROLE_JURY')) {
            return $this->render('AppBundle:Jury:personalInformations.html.twig', [
                'jury' => $form->createView(),
            ]);
        }
        if ($user->hasRole('ROLE_STUDENT')){
            return $this->render('AppBundle:Student:personalInformations.html.twig', [
                'student' => $form->createView(),
            ]);
        }
    }

    public function changerMotDePasseAction(Request $request){

        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class);

        if ($request->isMethod('post')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $newPassword = $form->getData();
                $user->setPlainPassword($newPassword['password']);
                $userManager = $this->get('fos_user.user_manager');
                $userManager->updatePassword($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Vos informations ont bien été mises a jour!'
                );
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('AppBundle:Common:changePassword.html.twig', [
            'changePassword' => $form->createView(),
        ]);
    }
}