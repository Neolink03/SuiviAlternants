<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AfterCourseTrigger;
use AppBundle\Entity\CompanyTrigger;
use AppBundle\Entity\DatetimeCondition;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\State;
use AppBundle\Entity\StudentCountCondition;
use AppBundle\Entity\Transition;
use AppBundle\Entity\TransitionCondition;
use AppBundle\Forms\Types\TransitionConditions\DatetimeConditionType;
use AppBundle\Forms\Types\TransitionConditions\StudentCountConditionType;
use AppBundle\Forms\Types\Workflow\ComplexStateType;
use AppBundle\Forms\Types\Workflow\SampleTransitionType;
use ReflectionClass;
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
            if (pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION) == 'yml'){
                $this->get('app.factory.workflow.custom')->createAppWorflowFromYml($promotion, $file->getPathname());
                $this->addFlash('success', 'La workflow a été ajouté à la formation');

                return $this->redirectToRoute('course_manager.promotion.workflow.edit', [
                    'promotionId' => $promotion->getId()
                ]);
            }else{
                $this->addFlash('danger', 'Le fichier fournit pour le workflow n\'est pas valide. Veuillez choisir un autre fichier (au format yml) ou créer votre workflow.');
            }

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
        $state->setJuryCanEdit(false);

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
            $state->setJuryCanEdit(false);
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
                'formTransition' => $formTransition->createView(),
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
        $startTransitions = $this->getDoctrine()->getRepository(Transition::class)->findBy(
            ['startState' => $state]
        );
        $endTransition = $this->getDoctrine()->getRepository(Transition::class)->findBy(
            ['endState' => $state]
        );

        foreach ($startTransitions as $transition){
            $em->remove($transition);
        }
        foreach ($endTransition as $transition){
            $em->remove($transition);
        }
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
    public function editStateWorkflowAction(Request $request , State $state, Promotion $promotion)
    {

        $em =$this->getDoctrine()->getManager();

        $selected = '';
        if ($state->getTrigger()) {
            $reflect = new ReflectionClass($state->getTrigger());
            $selected= $reflect->getShortName();
        }

            $form = $this->createForm(ComplexStateType::class, null, [
                'triggersAviable' => [
                    '' => '',
                    'Affiche un formulaire entreprise' => 'CompanyTrigger',
                    'Affiche un formulaire après la fin des études' => 'AfterCourseTrigger',
                ],
                'stateName' => $state->getName(),
                'juryCanEdit' => $state->getJuryCanEdit(),
                'triggersSelected' => $selected
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $state->setName($data['name']);
                $state->setJuryCanEdit($data['juryCanEdit']);
                switch ($data['trigger']) {
                    case "CompanyTrigger":
                        $trigger = new CompanyTrigger();
                        $trigger->setState($state);
                        $em->persist($trigger);
                        $state->setTrigger($trigger);
                        break;
                    case "AfterCourseTrigger":
                        $trigger = new AfterCourseTrigger();
                        $trigger->setState($state);
                        $em->persist($trigger);
                        $state->setTrigger($trigger);
                        break;
                    case "":
                        if(!is_null($state->getTrigger()))
                            $em->remove($state->getTrigger());
                        break;
                    default:
                        throw new \DomainException("Problème dans le choix du trigger");
                }
                $em->persist($state);
                $em->flush();

                return $this->redirectToRoute('course_manager.promotion.workflow.edit', [
                    'promotionId' => $promotion->getId()
                ]);
            }

            return $this->render('AppBundle:CourseManager:editWorkflowState.html.twig',
                [
                    'form' => $form->createView(),
                    'state' => $state,
                    'promotionId' => $promotion->getId()
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
            
            $promotion->getWorkflow()->renameTransition($transition->getName());
            $em->persist($promotion);
            
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
        $condition->setNumber($promotion->getStudentNumber());
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
                'form' => $form->createView(),
                'promotionId' => $promotion->getId(),
                'transitionId' => $transition->getId()
            ]);
    }

    /**
     * @ParamConverter("promotion", options={"mapping": {"promotionId" : "id"}})
     * @ParamConverter("condition", options={"mapping": {"conditionId" : "id"}})
     */
    public function editConditionWorkflowAction(Request $request, Promotion $promotion, TransitionCondition $condition)
    {
        $em =$this->getDoctrine()->getManager();

        if ($condition instanceof StudentCountCondition) {
            $form = $this->createForm(StudentCountConditionType::class, $condition);
            $titre = 'Modification de la condition sur le nombre d\'étudiants';
        }
        if ($condition instanceof DatetimeCondition) {
            $form = $this->createForm(DatetimeConditionType::class, $condition);
            $titre = 'Modification de la condition sur une date';
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
                'form' => $form->createView(),
                'titre' => $titre,
                'promotionId' => $promotion->getId(),
                'transitionId' => $condition->getTransition()->getId()
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
                'form' => $form->createView(),
                'promotionId' => $promotion->getId(),
                'transitionId' => $transition->getId()
            ]);
    }
}
