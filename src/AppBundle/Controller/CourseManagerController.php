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
use AppBundle\Entity\Promotion;
use AppBundle\Entity\User\CourseManager;
use AppBundle\Entity\User\Student;
use AppBundle\Forms\Types\AddPromotionType;
use AppBundle\Forms\Types\Courses\EditCourseType;
use AppBundle\Forms\Types\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CourseManagerController extends Controller
{
    /**
     * @ParamConverter("courseManager", options={"mapping": {"managerId" : "id"}})
     */
    public function courseManagerIndexAction(CourseManager $courseManager){

        $courseManaged = $courseManager->getCourseManaged()->toArray();
        $courseCoManaged = $courseManager->getCourseCoManaged()->toArray();
        $allCourses = array_merge($courseManaged, $courseCoManaged);

        return $this->render('AppBundle:CourseManager:home.html.twig',[
            'coursesManaged' => $allCourses,
        ]);
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     */
    public function addStudentAction(Promotion $promotion, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $student = new Student();
        $studentForm = $this->createForm(UserType::class, $student);
        $courseManager = $this->getUser();

        if ($request->isMethod('post')) {

            $studentForm->handleRequest($request);

            if ($studentForm->isSubmitted() && $studentForm->isValid()) {
                $studentInformation = $studentForm->getData();

                //A modifier
                $application = New Application();
                $application->setPromotion($promotion);
                $studentInformation->setUsername($studentInformation->getEmail());
                $studentInformation->setPlainPassword("FakePassword");
                $studentInformation->addApplication($application);

                $em->persist($studentInformation);
                $em->flush();
                return $this->redirectToRoute('courseManager.home', ['managerId' => $courseManager->getId()]);
            }
        }

        return $this->render('AppBundle:CourseManager:createStudent.html.twig', [
            'student' => $studentForm->createView(),
            'promotion' => $promotion,
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
    public function viewApplicationAction(Application $application)
    {
        return $this->render('AppBundle:CourseManager:viewApplication.html.twig', [
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