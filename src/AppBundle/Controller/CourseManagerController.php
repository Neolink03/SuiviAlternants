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
use AppBundle\Entity\Transition;
use AppBundle\Entity\User\Student;
use AppBundle\Entity\WorkFlow;
use AppBundle\Forms\Types\AddPromotionType;
use AppBundle\Forms\Types\Applications\ChangeStatusType;
use AppBundle\Forms\Types\CourseManagerType;
use AppBundle\Forms\Types\Courses\EditCourseType;
use AppBundle\Forms\Types\EmailMessageType;
use AppBundle\Forms\Types\PromotionFormType;
use AppBundle\Forms\Types\SearchStudentType;
use AppBundle\Forms\Types\StudentsCsvType;
use AppBundle\Forms\Types\UserType;
use AppBundle\Forms\Types\Workflow\StateType;
use AppBundle\Forms\Types\Workflow\TransitionType;
use AppBundle\Forms\Types\WorkflowYmlType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\XmlFileLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CourseManagerController extends Controller
{
    public function courseManagerIndexAction()
    {
        $courseManager = $this->getUser();
        $courseManaged = $courseManager->getCourseManaged()->toArray();
        $courseCoManaged = $courseManager->getCourseCoManaged()->toArray();
        $allCourses = array_merge($courseManaged, $courseCoManaged);

        return $this->render('AppBundle:CourseManager:home.html.twig', [
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

        $promotions ? $promotion = $promotions[0] : $promotion = null;
        $applications = $promotion->getApplications();

        $promotionsForm = $this->createForm(PromotionFormType::class, null, ["promotions" => $promotions]);

        $studentsCsvForm = $this->createForm(StudentsCsvType::class);

        $states = $em->getRepository(State::class)->findBy(
            ['workflow' => $promotion->getWorkflow()]
        );

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
        foreach ($transitions as $index => $value) {
            $stringTransitions[$value->getName()] = $value;
        }
        $form = $this->createForm(ChangeStatusType::class, null, array('transitions' => $stringTransitions));


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $application = $this->get('app.application')->setState($application, $data);

            $transitions = $workflow->getEnabledTransitions($application);
            $stringTransitions = [];
            foreach ($transitions as $index => $value) {
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

        return $this->redirectToRoute('course_manager.course.edit', ['courseId' => $courseId]);
    }

    public function studentListAction(Request $request)
    {
        $students = $this->getDoctrine()->getManager()
            ->getRepository(Student::class)
            ->findAll();

        return $this->render('AppBundle:Student:list.html.twig', [
            "students" => $students
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
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     */
    public function addApplicationWorkflowAction(Promotion $promotion, Request $request)
    {
        $form = $this->createForm(WorkflowYmlType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->getData()['file'];
            $this->get('app.factory.workflow.custom')->createAppWorflowFromYml($promotion, $file->getPathname());

            $this->addFlash('success', 'La workflow a été ajouté à la formation');

            return $this->redirectToRoute('course_manager.promotion.workflow.edit', [
                'promotionId' => $promotion->getId()
            ]);
        }

        return $this->render('AppBundle:CourseManager:addWorkflowFromYml.html.twig', [
            'promotion' => $promotion,
            'form' => $form->createView()
        ]);
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     */
    public function addApplicationWorkflowFromNothingAction(Promotion $promotion, Request $request)
    {
        $workflow = new WorkFlow();
        $workflow->setPromotion($promotion);

        $em = $this->getDoctrine()->getManager();
        $em->persist($workflow);
        $em->flush();

        return $this->redirectToRoute('course_manager.promotion.workflow.edit', [
            'promotionId' => $promotion->getId()
        ]);
    }


    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     */
    public function editApplicationWorkflowAction(Request $request, Promotion $promotion)
    {
        $state = new State();
        $transition = new Transition();

        $formState = $this->createForm(StateType::class, $state);
        $formTransition = $this->createForm(TransitionType::class, $transition, array(
            'states' => $promotion->getWorkflow()->getStates()
        ));

        $formState->handleRequest($request);
        $formTransition->handleRequest($request);

        $referer = $request->headers->get('referer');
        if ($formState->isSubmitted() && $formState->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $state->setWorkflow($promotion->getWorkflow());
            $em->persist($state);
            $em->flush();
            $this->addFlash('success', 'L\'état a été ajouté au workflow');
            return $this->redirect($referer);
        }

        if ($formTransition->isSubmitted() && $formTransition->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $transition->setWorkflow($promotion->getWorkflow());
            $em->persist($transition);
            $em->flush();
            $this->addFlash('success', 'La transition a été ajoutée au workflow');
            return $this->redirect($referer);
        }

        return $this->render('AppBundle:CourseManager:editWorkflow.html.twig',
            [
                'workflowDump' => $this->get('app.factory.workflow')->dumpWorflowFromPromotion($promotion),
                'promotion' => $promotion,
                'formState' => $formState->createView(),
                'formTransition' => $formTransition->createView()
            ]);
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     */
    public function exportApplicationWorkflowAction(Request $request, Promotion $promotion)
    {
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($promotion->getWorkflow(), 'yml');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/yaml');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $promotion->getCourse()->getName() . '.yml";');
        return $response;
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     * @ParamConverter("state", options={"mapping": {"stateId" : "id"}})
     */
    public function deleteStateWorkflowAction(Request $request ,Promotion $promotion, State $state)
    {
        $em =$this->getDoctrine()->getManager();
        $promotion->getWorkflow()->removeState($state);
        $em->persist($promotion);
        $em->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    public function displayPersonalInformationsAction(Request $request){

        $courseManager = $this->getUser();
        $form = $this->createForm(CourseManagerType::class, $courseManager, ['isDisabled' => true]);

        if ($request->isMethod('post')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($courseManager);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Vos informations ont bien été mises a jour!'
                );
            }elseif($form->isSubmitted() && !$form->isValid()){
                $this->addFlash(
                    'danger',
                    'Une ou plusieurs informations sont manquantes et/ou non valides    '
                );
            }
        }

        return $this->render('AppBundle:CourseManager:personalInformations.html.twig',[
            'courseManager' => $form->createView(),
        ]);
    }



}