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
use AppBundle\Entity\State;
use AppBundle\Entity\User\Jury;
use AppBundle\Entity\User\Student;
use AppBundle\Forms\Types\AddJuryType;
use AppBundle\Forms\Types\AddPromotionType;
use AppBundle\Forms\Types\Applications\ChangeStatusType;
use AppBundle\Forms\Types\Courses\CourseType;
use AppBundle\Forms\Types\EmailMessageType;
use AppBundle\Forms\Types\PromotionFormType;
use AppBundle\Forms\Types\SearchStudentType;
use AppBundle\Forms\Types\StudentsCsvType;
use AppBundle\Forms\Types\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class CourseManagerController extends Controller
{
    public function courseManagerIndexAction()
    {
        $courseManager = $this->getUser();
        $courseManaged = $courseManager->getCourseManaged()->toArray();
        $courseCoManaged = $courseManager->getCourseCoManaged()->toArray();
        $allCourses = array_merge($courseManaged, $courseCoManaged);

        return $this->render('AppBundle:CourseManager:home.html.twig', [
            'coursesManaged' => $allCourses
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

        if ($request->isMethod('post')) {

            $studentForm->handleRequest($request);

            if ($studentForm->isSubmitted() && $studentForm->isValid()) {

                $userFactory = $this->get('app.factory.user');
                $student = $userFactory->getOrCreateStudentIfNotExist($student);
                $student = $userFactory->createStudentApplicationFromPromotion($student, $promotion);
                $em->persist($student);
                $em->flush();
                return $this->redirectToRoute('course_manager.course', ['courseId' => $promotion->getCourse()->getId()]);
            }
        }

        return $this->render('AppBundle:CourseManager:createStudent.html.twig', [
            'student' => $studentForm->createView(),
            'promotion' => $promotion,
        ]);
    }

    /**
     * @ParamConverter("course", options={"mapping": {"courseId" : "id"}})
     */
    public function detailsCourseAction(Request $request, Course $course)
    {
        $em = $this->getDoctrine()->getManager();
        $promotions = $em->getRepository(Promotion::class)->findBy(
            ['course' => $course],
            ['id' => 'desc']
        );

        $states = null;
        $applications = null;

        if ($promotions) {
            $promotion = $promotions[0];
            $applications = $promotion->getApplications();
            $states = $em->getRepository(State::class)->findBy(
                ['workflow' => $promotion->getWorkflow()]
            );
        } else {
            $promotion = null;
        }

        $promotionsForm = $this->createForm(PromotionFormType::class, null, ["promotions" => $promotions]);
        $studentsCsvForm = $this->createForm(StudentsCsvType::class);
        $searchForm = $this->createForm(SearchStudentType::class, null, ['states' => $states]);

        if ($request->get('promotion')) {
            $promotion = $em->getRepository(Promotion::class)->find($request->get('promotion'));
            $promotionsForm->get('promotions')->setData($promotion);
        }

        if ($request->isMethod('post')) {
            $promotionsForm->handleRequest($request);
            $studentsCsvForm->handleRequest($request);
            $searchForm->handleRequest($request);

            if ($promotionsForm->isSubmitted() && $promotionsForm->isValid()) {
                $promotion = $em->getRepository(Promotion::class)->find($promotionsForm->getData()['promotions']->getId());
                $applications = $promotion->getApplications();
            }

            if ($studentsCsvForm->isSubmitted() && $studentsCsvForm->isValid()) {
                /** @var UploadedFile $file */
                $file = $studentsCsvForm->getData()['file'];
                $this->get('app.factory.user')->saveStudentsfromCsvFile($file->getPathname(), $promotion);
            }

            if ($searchForm->isSubmitted() && $searchForm->isValid()) {
                $applications = $em->getRepository(Application::class)->findAllByFilters($searchForm->getData());
            }
        }

        return $this->render('@App/CourseManager/detailsCourse.html.twig', [
            'course' => $course,
            'promotion' => $promotion,
            'applications' => $applications,
            'promotionsForm' => $promotionsForm->createView(),
            'studentsCsvForm' => $studentsCsvForm->createView(),
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @ParamConverter("course", options={"mapping": {"courseId" : "id"}})
     */
    public function editCourseAction(Request $request, Course $course)
    {
        $em = $this->getDoctrine()->getManager();
        $editCourseForm = $this->createForm(CourseType::class, $course);

        if ($request->isMethod('post')) {

            $editCourseForm->handleRequest($request);

            if ($editCourseForm->isSubmitted() && $editCourseForm->isValid()) {
                $em->persist($course);
                $em->flush();
                $this->addFlash('success', 'La formation a été modifiée avec succès.');
                return $this->redirectToRoute('course_manager.course', ['courseId' => $course->getId()]);
            }
        }

        return $this->render('AppBundle:Course:edit.html.twig', [
            "courseForm" => $editCourseForm->createView(),
            "title" => "Modifier formation",
            "updateCourseActionUrl" => $this->generateUrl("course_manager.course.edit", ["courseId" => $course->getId()])
        ]);
    }

    /**
     * @ParamConverter("application", options={"mapping": {"applicationId" : "id"}})
     */
    public function viewApplicationAction(Request $request, Application $application)
    {
        $workflow = $this->get('app.factory.workflow')->generateWorflowFromApplication($application);
        $transitions = $workflow->getEnabledTransitions($application);

        $realTransitions = $application->getPromotion()->getWorkflow()->getTransitions()->toArray();

        $result = [];
        foreach ($realTransitions as $realTransition) {
            foreach ($transitions as $workflowTransition) {
                if ($realTransition->getMachineName() == $workflowTransition->getName()) {
                    $result[] = $realTransition;
                }
            }
        }

        $form = $this->createForm(ChangeStatusType::class, null, array('transitions' => $result));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $application = $this->get('app.application')->setState($application, $data);

            $transitions = $workflow->getEnabledTransitions($application);
            $result = [];
            foreach ($realTransitions as $realTransition) {
                foreach ($transitions as $workflowTransition) {
                    if ($realTransition->getMachineName() == $workflowTransition->getName()) {
                        $result[] = $realTransition;
                    }
                }
            }
            $form = $this->createForm(ChangeStatusType::class, null, array('transitions' => $result));
        }
        return $this->render('AppBundle:CourseManager:viewApplication.html.twig', [
            'form' => $form->createView(),
            'application' => $application
        ]);
    }

    /**
     * @ParamConverter("course", options={"mapping": {"courseId" : "id"}})
     */
    public function addPromotionAction(Request $request, Course $course)
    {
        $addPromotionForm = $this->createForm(AddPromotionType::class);

        if ($request->isMethod('post')) {
            $data = $request->request->get('add_promotion');
            $addPromotionForm->handleRequest($request);

            if ($addPromotionForm->isSubmitted() && $addPromotionForm->isValid()) {
                $this->get('app.factory.promotion')->createPromotionFromForm($course->getId(), $data);
                $this->addFlash('success', 'La promotion a été ajoutée avec succès.');
                return $this->redirectToRoute('course_manager.course', ['courseId' => $course->getId()]);
            }
        }

        return $this->render('AppBundle:CourseManager:addPromotion.html.twig', [
            'addPromotionForm' => $addPromotionForm->createView(),
            'course' => $course
        ]);
    }

    public function studentListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $courseManager = $this->getUser();
        $courseManaged = $courseManager->getCourseManaged()->toArray();
        $courseCoManaged = $courseManager->getCourseCoManaged()->toArray();
        $courses = array_merge($courseManaged, $courseCoManaged);

        $students = $this->getDoctrine()->getManager()
            ->getRepository(Student::class)
            ->findByCourses($courses);

        $searchForm = $this->createForm(SearchStudentType::class, null, []);

        if ($request->isMethod('post')) {
            $searchForm->handleRequest($request);
            if ($searchForm->isSubmitted() && $searchForm->isValid()) {
                $students = $em->getRepository(Student::class)->findByCoursesWithFilters(
                    $courses,
                    $searchForm->getData()
                );
            }
        }

        return $this->render('AppBundle:Student:list.html.twig', [
            "students" => $students,
            "searchForm" => $searchForm->createView()
        ]);
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     */
    public function sendMailAction(Request $request, Promotion $promotion)
    {
        $form = $this->createForm(EmailMessageType::class, null, array(
            'applications' => $promotion->getApplications()->toArray()
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $mailRecipients = [];
            foreach ($data['users'] as $key => $application) {
                $mailRecipients[] = $application->getStudent()->getEmail();
            };

            $swiftMail = $this->get('app.factory.swift_message')->create(
                $data['object'],
                "uneadresse@hotmail.com",
                $mailRecipients,
                "AppBundle:email:contact.html.twig",
                array(
                    "message" => $data['message']
                )
            );
            $this->get('mailer')->send($swiftMail);
            $this->addFlash("success", "Email envoyé à tous les destinataires");
        }

        return $this->render('AppBundle:CourseManager:sendEmail.html.twig', [
            'promotion' => $promotion,
            'form' => $form->createView()
        ]);
    }

    /**
     * @ParamConverter("course", options={"mapping": {"courseId" : "id"}})
     */
    public function addJuryAction(Request $request, Course $course){

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(AddJuryType::class, null, array(
            'applications' => $jury = $this->getDoctrine()->getManager()->getRepository(Jury::class)->findAll()
        ));

        if ($request->isMethod('post')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $data = $form->getData();

                $course->setJurys($data['jurys']);
                $em->persist($course);
                $em->flush();
                $this->addFlash("success", "Les jurys ont bien été ajoutés à la promotion");
            }
        }

        return $this->render('AppBundle:CourseManager:addJuryToCourse.html.twig',[
            'juryList' => $form->createView(),
            'course' => $course
        ]);
    }
}