<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 05/05/2017
 * Time: 16:03
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Application;
use AppBundle\Entity\Course;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\State;
use AppBundle\Forms\Types\PromotionFormType;
use AppBundle\Forms\Types\SearchStudentType;
use AppBundle\Forms\Types\StudentsCsvType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class JuryController extends Controller
{
    public function juryIndexAction(Request $request){

        $jury = $this->getUser();

        if(count($jury->getCoursesAttached()->toArray()))
            $courseAttached = $jury->getCoursesAttached()->toArray();

        if(count($jury->getCoursesAttached()->toArray())){
            return $this->render('AppBundle:Jury:home.html.twig', [
                'courseAttached' => $courseAttached
            ]);
        }
        else{
            return $this->render('AppBundle:Jury:home.html.twig');
        }
    }

//    /**
//     * @ParamConverter("course", options={"mapping": {"courseId" : "id"}})
//     */
//    public function studentListAction(Request $request, Course $course){
//
//        $em = $this->getDoctrine()->getManager();
//        $promotions = $em->getRepository(Promotion::class)->findBy(
//            ['course' => $course],
//            ['id' => 'desc']
//        );
//
//        $states = null;
//        $applications = null;
//
//        if ($promotions) {
//            $promotion = $promotions[0];
//            $applications = $promotion->getApplications();
//            $states = $em->getRepository(State::class)->findBy(
//                ['workflow' => $promotion->getWorkflow()]
//            );
//        } else {
//            $promotion = null;
//        }
//
//        $promotionsForm = $this->createForm(PromotionFormType::class, null, ["promotions" => $promotions]);
//        $searchForm = $this->createForm(SearchStudentType::class, null, ['states' => $states]);
//
//        if ($request->get('promotion')) {
//            $promotion = $em->getRepository(Promotion::class)->find($request->get('promotion'));
//            $promotionsForm->get('promotions')->setData($promotion);
//        }
//
//        if ($request->isMethod('post')) {
//            $promotionsForm->handleRequest($request);
//            $searchForm->handleRequest($request);
//
//            if ($promotionsForm->isSubmitted() && $promotionsForm->isValid()) {
//                $promotion = $em->getRepository(Promotion::class)->find($promotionsForm->getData()['promotions']->getId());
//                $applications = $promotion->getApplications();
//            }
//
//            if ($searchForm->isSubmitted() && $searchForm->isValid()) {
//                $applications = $em->getRepository(Application::class)->findAllByFilters($searchForm->getData());
//            }
//        }
//
//        return $this->render('@App/Jury/studentList.html.twig', [
//            'course' => $course,
//            'promotion' => $promotion,
//            'applications' => $applications,
//            'promotionsForm' => $promotionsForm->createView(),
//            'searchForm' => $searchForm->createView()
//        ]);
//    }


    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     */
    public function studentListAction(Request $request, Promotion $promotion)
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
        $searchForm = $this->createForm(SearchStudentType::class, null, ['states' => $states]);

        if ($request->isMethod('post')) {
            $promotionsForm->handleRequest($request);
            $searchForm->handleRequest($request);

            if ($promotionsForm->isSubmitted() && $promotionsForm->isValid()) {
                return $this->redirectToRoute('jury.promotion', ['promotionId' => $promotionsForm->getData()['promotions']->getId()]);
            }

            if ($searchForm->isSubmitted() && $searchForm->isValid()) {
                $applications = $em->getRepository(Application::class)->findAllByPromotionAndFilters($promotion, $searchForm->getData());
            }
        }

        return $this->render('@App/Jury/studentList.html.twig', [
            'course' => $promotion->getCourse(),
            'promotion' => $promotion,
            'applications' => $applications,
            'promotionsForm' => $promotionsForm->createView(),
            'searchForm' => $searchForm->createView()
        ]);
    }

}