<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 09:57
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Course;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\User\Student;
use AppBundle\Forms\Types\AddPromotionType;
use AppBundle\Forms\Types\Courses\EditCourseType;
use AppBundle\Forms\Types\UserType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

        $promotion = $promotions[0];

        $promotionsForm = $this->createFormBuilder()
            ->add('promotions', EntityType::class, [
                'class' => Promotion::class,
                'choices' => $promotions,
                'choice_label' => function (Promotion $promotion) {
                    return $promotion->getName();
                },
                'label' => 'Promotion'
            ])
            ->add('submit', SubmitType::class, ['label' => 'Choisir'])
            ->getForm();

        if ($request->get('promotion')) {
            $promotionsForm->get('promotions')->setData($em->getRepository(Promotion::class)->find($request->get('promotion')));
        }

        if ($request->isMethod('post')) {

            $promotionsForm->handleRequest($request);

            if ($promotionsForm->isSubmitted() && $promotionsForm->isValid()) {
                $promotion = $em->getRepository(Promotion::class)->find($promotionsForm->getData()['promotions']->getId());
            }
        }

        return $this->render('@App/CourseManager/detailsCourse.html.twig', [
            'course' => $course,
            'promotion' => $promotion,
            'promotionsForm' => $promotionsForm->createView()
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
     * @ParamConverter("course", options={"mapping": {"courseId" : "id"}})
     */
    public function addPromotionAction(Request $request, Course $course)
    {
        $data = $request->request->get('add_promotion');

        $this->get('app.factory.promotion')->createPromotionFromForm($course->getId(), $data);

        $this->addFlash('success', 'La promotion a été ajoutée avec succès.');

        return $this->redirectToRoute('course_manager.course.edit', ['courseId' => $course->getId()]); // Redirect to CourseList
    }

    public function studentListAction(Request $request)
    {
        return $this->render('::base.html.twig', [
        ]);
    }
}