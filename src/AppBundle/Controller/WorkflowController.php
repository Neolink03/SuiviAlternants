<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DatetimeCondition;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\State;
use AppBundle\Entity\StudentCountCondition;
use AppBundle\Entity\Transition;
use AppBundle\Entity\TransitionCondition;
use AppBundle\Forms\Types\TransitionConditions\DatetimeConditionType;
use AppBundle\Forms\Types\TransitionConditions\StudentCountConditionType;
use AppBundle\Forms\Types\Workflow\SampleTransitionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\WorkFlow;
use AppBundle\Forms\Types\Workflow\StateType;
use AppBundle\Forms\Types\Workflow\TransitionType;
use AppBundle\Forms\Types\WorkflowYmlType;

class WorkflowController extends Controller
{
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

        $state = new State();
        $state->setName("Départ");
        $state->setWorkflow($workflow);

        $workflow->addState($state);
        $workflow->setPromotion($promotion);

        $this->addFlash('success', 'Le workflow a été créé avec un état de départ. Vous pouvez néanmoins continuez de l\'éditer.');

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
        $formTransition = $this->createForm(SampleTransitionType::class, $transition, array(
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
        $em->remove($state);
        $em->flush();
        
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     * @ParamConverter("state", options={"mapping": {"stateId" : "id"}})
     */
    public function renameStateWorkflowAction(Request $request , State $state, Promotion $promotion)
    {
        $em =$this->getDoctrine()->getManager();

        $form = $this->createForm(StateType::class, $state);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($state);
            
            $promotion->getWorkflow()->renameState($state->getName());
            $em->persist($promotion);
            
            $em->flush();

            return $this->redirectToRoute('course_manager.promotion.workflow.edit', [
                'promotionId' => $promotion->getId()
            ]);
        }

        return $this->render('AppBundle:CourseManager:editWorkflowState.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     * @ParamConverter("transition", options={"mapping": {"transitionId" : "id"}})
     */
    public function deleteTransitionWorkflowAction(Request $request , Promotion $promotion, Transition $transition)
    {
        $em =$this->getDoctrine()->getManager();
        $promotion->getWorkflow()->removeTransition($transition);
        $em->persist($promotion);
        $em->remove($transition);
        $em->flush();
        
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
    
    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     * @ParamConverter("transition", options={"mapping": {"transitionId" : "id"}})
     */
    public function renameTransitionWorkflowAction(Request $request , Transition $transition, Promotion $promotion)
    {
        $em =$this->getDoctrine()->getManager();

        $form = $this->createForm(SampleTransitionType::class, $transition, array(
            'states' => $promotion->getWorkflow()->getStates()
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($transition);
            $em->flush();

            return $this->redirectToRoute('course_manager.promotion.workflow.edit', [
                'promotionId' => $promotion->getId()
            ]);
        }
        return $this->render('AppBundle:CourseManager:editWorkflowTransition.html.twig',
            [
                'form' => $form->createView(),
                'transition' => $transition,
                'promotion' => $promotion
            ]);
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     * @ParamConverter("condition", options={"mapping": {"conditionId" : "id"}})
     */
    public function deleteConditionWorkflowAction(Request $request , TransitionCondition $condition, Promotion $promotion)
    {
        $em =$this->getDoctrine()->getManager();
        $condition->getTransition()->setCondition(null);
        $em->persist($condition);
        $em->flush();
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     * @ParamConverter("transition", options={"mapping": {"transitionId" : "id"}})
     */
    public function addConditionWorkflowCountAction(Request $request, Promotion $promotion, Transition $transition)
    {
        $em =$this->getDoctrine()->getManager();

        $condition = new StudentCountCondition();
        $form = $this->createForm(StudentCountConditionType::class, $condition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $condition->setTransition($transition);
            $transition->setCondition($condition);
            $em->persist($transition);
            $em->flush();

            return $this->redirectToRoute('course_manager.promotion.workflow.transition.rename', [
                'promotionId' => $promotion->getId(),
                'transitionId' => $transition->getId()
            ]);
        }
        return $this->render('AppBundle:CourseManager:addWorkflowConditionCount.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     * @ParamConverter("condition", options={"mapping": {"conditionId" : "id"}})
     */
    public function editConditionWorkflowCountAction(Request $request, Promotion $promotion, TransitionCondition $condition)
    {
        $em =$this->getDoctrine()->getManager();

        if ($condition instanceof StudentCountCondition) {
            $form = $this->createForm(StudentCountConditionType::class, $condition);
        }
        if ($condition instanceof DatetimeCondition) {
            $form = $this->createForm(DatetimeConditionType::class, $condition);
        }

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($condition);
            $em->flush();

            return $this->redirectToRoute('course_manager.promotion.workflow.transition.rename', [
                'promotionId' => $promotion->getId(),
                'transitionId' => $condition->getTransition()->getId()
            ]);
        }

        return $this->render('AppBundle:CourseManager:editWorkflowConditionCount.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     * @ParamConverter("transition", options={"mapping": {"transitionId" : "id"}})
     */
    public function addConditionWorkflowDateAction(Request $request, Promotion $promotion, Transition $transition)
    {
        $em =$this->getDoctrine()->getManager();

        $condition = new DatetimeCondition();
        $form = $this->createForm(DatetimeConditionType::class, $condition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $condition->setTransition($transition);
            $transition->setCondition($condition);
            $em->persist($transition);
            $em->flush();

            return $this->redirectToRoute('course_manager.promotion.workflow.transition.rename', [
                'promotionId' => $promotion->getId(),
                'transitionId' => $transition->getId()
            ]);
        }
        return $this->render('AppBundle:CourseManager:addWorkflowConditionDate.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

}
