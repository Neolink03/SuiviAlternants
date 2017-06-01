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
use Symfony\Component\HttpFoundation\JsonResponse;

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
                return $this->redirectToRoute('course_manager.promotion', ['promotionId' => $promotion->getId()]);
            }
        }

        return $this->render('AppBundle:CourseManager:createStudent.html.twig', [
            'student' => $studentForm->createView(),
            'promotion' => $promotion,
        ]);
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
      * @ParamConverter("student", options={"mapping": {"studentId" : "id"}})
     */
    public function addExistingStudentAction(Request $request, Promotion $promotion, Student $student)
    {
        $em = $this->getDoctrine()->getManager();
        $userFactory = $this->get('app.factory.user');
        $userFactory->getOrCreateStudentIfNotExist($student);
        $student = $userFactory->createStudentApplicationFromPromotion($student, $promotion);
        $em->persist($student);
        $em->flush();
        return $this->redirectToRoute('course_manager.promotion', ['promotionId' => $promotion->getId()]);
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     */
    public function detailsPromotionAction(Request $request, Promotion $promotion)
    {
        $em = $this->getDoctrine()->getManager();

        $applications = $promotion->getApplications();
        $states = $em->getRepository(State::class)->findBy(
            ['workflow' => $promotion->getWorkflow()]
        );

        $promotionsForm = $this->createForm(PromotionFormType::class, null, [
            "promotions" => $promotion->getCourse()->getPromotions(),
            "promotionSelected" => $promotion
        ]);
        $studentsCsvForm = $this->createForm(StudentsCsvType::class);
        $searchForm = $this->createForm(SearchStudentType::class, null, ['states' => $states]);

        if ($request->isMethod('post')) {
            $promotionsForm->handleRequest($request);
            $studentsCsvForm->handleRequest($request);
            $searchForm->handleRequest($request);

            if ($promotionsForm->isSubmitted() && $promotionsForm->isValid()) {
                return $this->redirectToRoute('course_manager.promotion', ['promotionId' => $promotionsForm->getData()['promotions']->getId()]);
            }

            if ($studentsCsvForm->isSubmitted() && $studentsCsvForm->isValid()) {
                /** @var UploadedFile $file */
                $file = $studentsCsvForm->getData()['file'];
                if (pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION) == 'csv')
                    $this->get('app.factory.user')->saveStudentsfromCsvFile($file->getPathname(), $promotion);
                else
                    $this->addFlash('danger', 'Le fichier fournit pour ajouter des étudiants n\'est pas valide. Veuillez choisir un autre fichier (au format csv) ou ajouter les étudiants un à un.');
            }

            if ($searchForm->isSubmitted() && $searchForm->isValid()) {
                $applications = $em->getRepository(Application::class)->findAllByPromotionAndFilters($promotion, $searchForm->getData());
            }
        }

        return $this->render('@App/CourseManager/detailsCourse.html.twig', [
            'course' => $promotion->getCourse(),
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
                return $this->redirectToRoute('course_manager.promotion', ['promotionId' => $course->getPromotions()->last()->getId()]);
            }
        }

        return $this->render('AppBundle:Course:edit.html.twig', [
            "courseForm" => $editCourseForm->createView(),
            "title" => "Modifier formation"
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

            $dateStartmodif = $data['startDate']['year'] . '-' . $data['startDate']['month'] . '-' . $data['startDate']['day'];
            $dateEndmodif = $data['endDate']['year'] . '-' . $data['endDate']['month'] . '-' . $data['endDate']['day'];
            if ($dateStartmodif <= $dateEndmodif) {

                if ($addPromotionForm->isSubmitted() && $addPromotionForm->isValid()) {
                    $this->get('app.factory.promotion')->createPromotionFromForm($course->getId(), $data);
                    $this->addFlash('success', 'La promotion a été ajoutée avec succès.');
                    return $this->redirectToRoute('course_manager.promotion', ['promotionId' => $course->getPromotions()->last()->getId()]);
                }
            } else {
                $this->addFlash('danger', 'La date de début de la formation ne peut être supérieure à la date de fin.');
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
    public function addJuryAction(Request $request, Course $course)
    {

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

        return $this->render('AppBundle:CourseManager:addJuryToCourse.html.twig', [
            'juryList' => $form->createView(),
            'course' => $course
        ]);
    }

    public function findStudentsAction(Request $request) {

      $em = $this->getDoctrine()->getManager();
      $nameKeyWord = $request->get('search');
      $students = $em->getRepository(Student::class)->findByNameLike($nameKeyWord);

      $data = [];
      foreach ($students as $student) {
        $data[] = [
          "id" => $student->getId(),
          "name" => $student->getFullName(),
          "email" => $student->getEmail()
        ];
      }

      return new JsonResponse($data, 200, array(
          'Cache-Control' => 'no-cache',
      ));
    }
}
