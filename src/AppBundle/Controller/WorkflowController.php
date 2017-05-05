<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Promotion;
use AppBundle\Entity\State;
use AppBundle\Entity\Transition;
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
    public function renameTransitionWorkflowAction(Request $request , Transition $transition, Promotion $promotion)
    {
        $em =$this->getDoctrine()->getManager();

        $form = $this->createForm(TransitionType::class, $transition, array(
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
                'form' => $form->createView()
            ]);
    }
}
