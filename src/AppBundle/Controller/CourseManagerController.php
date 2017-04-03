<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 09:57
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Application;
use AppBundle\Entity\Course;
use AppBundle\Entity\StatusModification;
use AppBundle\Entity\User\Student;
use AppBundle\Forms\Types\AddPromotionType;
use AppBundle\Forms\Types\Applications\ChangeStatusType;
use AppBundle\Forms\Types\Courses\EditCourseType;
use AppBundle\Forms\Types\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        if (!$course) {
            $this->addFlash('danger', 'La formation est introuvable.');
            // return $this->redirectToRoute(''); Redirect to CourseList
        }

        $editCourseForm = $this->createForm(EditCourseType::class, $course);
        $addPromotionForm = $this->createForm(AddPromotionType::class);

        if ($request->isMethod('post')) {

            $editCourseForm->handleRequest($request);

            if ($editCourseForm->isSubmitted() && $editCourseForm->isValid()) {
                $em->persist($course);
                $em->flush();
                $this->addFlash('success', 'La formation a été modifiée avec succès.');
            }
        }

        return $this->render('AppBundle:CourseManager:editCourse.html.twig', [
            'editCourseForm' => $editCourseForm->createView(),
            'addPromotionForm' => $addPromotionForm->createView()
        ]);
    }

    /**
     * @ParamConverter("application", options={"mapping": {"applicationId" : "id"}})
     */
    public function viewApplicationAction(Request $request, Application $application)
    {
        $workflow = $this->get('app.factory.workflow')->generateWorflowFromApplication($application);
        $transitions = $workflow->getEnabledTransitions($application);

        $stringTransitions = [];
        foreach ($transitions as $index => $value){
            $stringTransitions[$value->getName()] = $value;
        }
        $form = $this->createForm(ChangeStatusType::class, null, array('transitions' => $stringTransitions));


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $statusModif = new StatusModification();
            $statusModif->setApplication($application);
            $statusModif->setComment($data['comment']);
            $statusModif->setDateTime(new \DateTime());

            $application->addStatusModification($statusModif);
            $application->setCurrentState($data['transition']->getTos()[0]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();

            $transitions = $workflow->getEnabledTransitions($application);
            $stringTransitions = [];
            foreach ($transitions as $index => $value){
                $stringTransitions[$value->getName()] = $value;
            }
            $form = $this->createForm(ChangeStatusType::class, null, array('transitions' => $stringTransitions));
        }
        return $this->render('AppBundle:CourseManager:viewApplication.html.twig', [
            'form' => $form->createView(),
            'application' => $application
        ]);
    }

    public function addPromotionAction(Request $request)
    {
        $courseId = $request->get('courseId');
        $data = $request->request->get('add_promotion');

        $this->get('app.factory.promotion')->createPromotionFromForm($courseId, $data);

        $this->addFlash('success', 'La promotion a été ajoutée avec succès.');

        return $this->redirectToRoute('course_manager.course.edit', ['courseId' => $courseId]); // Redirect to CourseList
    }

    public function studentListAction(Request $request) {
        return $this->render('::base.html.twig', [
        ]);
    }
}