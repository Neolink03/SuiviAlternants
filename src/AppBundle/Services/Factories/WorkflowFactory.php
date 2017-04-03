<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services\Factories;


use AppBundle\Entity\Application;
use AppBundle\Entity\State;
use AppBundle\Entity\Transition;
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

        $workflow = $application->getPromotion()->getCourse()->getWorkflow();

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
        echo (new StateMachineGraphvizDumper)->dump($definition);
        $marking = new SingleStateMarkingStore('currentState');
        return new Workflow($definition, $marking);
    }
}