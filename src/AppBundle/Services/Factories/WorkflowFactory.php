<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services\Factories;


use AppBundle\Entity\Application;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\State;
use AppBundle\Entity\StatusModification;
use AppBundle\Entity\Transition;
use AppBundle\Models\CustomDefinitionBuilder;
use AppBundle\Models\CustomGraphvizDumper;
use AppBundle\Models\CustomTransition;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\Dumper\StateMachineGraphvizDumper;
use Symfony\Component\Workflow\MarkingStore\SingleStateMarkingStore;
use Symfony\Component\Workflow\Workflow;

class WorkflowFactory
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function generateWorflowFromApplication(Application $application) : Workflow{

        $workflow = $application->getPromotion()->getWorkflow();

        $builder = new DefinitionBuilder();

        $states = $workflow->getStates();
        /** @var State $state */
        foreach ($states as $state){
            $builder->addPlace($state->getMachineName());
        }

        $transitions = $workflow->getTransitions();

        /** @var Transition $transition */
        foreach ($transitions as $transition){
            $builder->addTransition(
                new \Symfony\Component\Workflow\Transition(
                    $transition->getMachineName(),
                    $transition->getStartState()->getMachineName(),
                    $transition->getEndState()->getMachineName())
            );
        }

        $definition = $builder->build();
        $marking = new SingleStateMarkingStore('currentState');
        return new Workflow($definition, $marking);
    }



    public function dumpWorflowFromApplication(Application $application) : string
    {
        $workflow = $application->getPromotion()->getWorkflow();

        $builder = $this->definitionBuilderFromWorkflow($workflow);
        $builder->setInitialPlace($application->getCurrentState());

        $definition = $builder->build();
        return (new StateMachineGraphvizDumper)->dump($definition);
    }

    private function definitionBuilderFromWorkflow(\AppBundle\Entity\WorkFlow $workflow) : DefinitionBuilder {
        $builder = new DefinitionBuilder();
        $states = $workflow->getStates();
        /** @var State $state */
        foreach ($states as $state){
            $builder->addPlace($state->getMachineName());
        }

        $transitions = $workflow->getTransitions();

        /** @var Transition $transition */
        foreach ($transitions as $transition){
            $builder->addTransition(
                new \Symfony\Component\Workflow\Transition(
                    $transition->getMachineName(),
                    $transition->getStartState()->getMachineName(),
                    $transition->getEndState()->getMachineName())
            );
        }

        return $builder;
    }

    public function dumpStudentWorflowFromApplication(Application $application) : string
    {
        $modifications = $application->getStatusModifications()->toArray();

        $builder = $this->definitionBuilderFromModifications($modifications);
        $definition = $builder->build();

        return (new CustomGraphvizDumper)->dump($definition);
    }

    private function definitionBuilderFromModifications(array $modifications) : CustomDefinitionBuilder {
        $builder = new CustomDefinitionBuilder();

        /** @var StatusModification $modification */
        foreach ($modifications as $index => $modification){
            $builder->addPlace( $modification->getState()->getName());
            if($index+1 < count($modifications)){
                $builder->addTransition(
                    new CustomTransition(
                        '',
                        $modifications[$index+1]->getState()->getName(),
                        $modification->getState()->getName())
                );
            }
        }

        return $builder;
    }

    public function customDumpWorflowFromApplication(Application $application) : string
    {
        $workflow = $application->getPromotion()->getWorkflow();

        $builder = $this->customDefinitionBuilderFromWorkflow($workflow);
        $builder->setInitialPlace($application->getLastStatusModification()->getState()->getName());

        $definition = $builder->build();
        return (new CustomGraphvizDumper())->dump($definition);
    }

    public function customDumpWorflowFromPromotion(Promotion $promotion) : string
    {
        $workflow = $promotion->getWorkflow();
        $builder = $this->customDefinitionBuilderFromWorkflow($workflow);
        $definition = $builder->build();

        return (new CustomGraphvizDumper())->dump($definition);
    }

    private function customDefinitionBuilderFromWorkflow(\AppBundle\Entity\WorkFlow $workflow) : CustomDefinitionBuilder {
        $builder = new CustomDefinitionBuilder();
        $states = $workflow->getStates();
        /** @var State $state */
        foreach ($states as $state){
            $builder->addPlace($state->getName());
        }

        $transitions = $workflow->getTransitions();

        /** @var Transition $transition */
        foreach ($transitions as $transition){
            $builder->addTransition(
                new CustomTransition(
                    $transition->getName(),
                    $transition->getStartState()->getName(),
                    $transition->getEndState()->getName())
            );
        }

        return $builder;
    }
}