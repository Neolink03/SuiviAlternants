<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services;


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

    public function generateWorflowFromTrainging(int $idTraining, int $year) : Workflow{

        $builder = new DefinitionBuilder();

        $states = $this->em->getRepository(State::class)->findAll();
        /** @var State $state */
        foreach ($states as $state){
            $builder->addPlace($state->getMachineName());
        }

        $transitions = $this->em->getRepository(Transition::class)->findAll();

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