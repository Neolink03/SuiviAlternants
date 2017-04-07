<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services\Factories;


use AppBundle\Entity\Course;
use AppBundle\Entity\State;
use AppBundle\Entity\Transition;
use AppBundle\Entity\WorkFlow;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Yaml\Yaml;

class AppWorkflowFactory
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createAppWorflowFromYml(Course $course, string $workflowYmlPath){
        $stateDictionary = [];
        list($states, $transitions) =array_values(Yaml::parse(file_get_contents($workflowYmlPath)));

        $workflow = new WorkFlow();

        foreach ($states as $state){
            $stateEntity = new State();
            $stateEntity->setName($state["name"]);
            $stateEntity->setMachineName($state["machineName"]);
            $workflow->addState($stateEntity);
            $stateDictionary[$state["machineName"]] = $stateEntity;
        }

        foreach ($transitions as $transition){
            $transitionEntity = new Transition();
            $transitionEntity->setName($transition["name"]);
            $transitionEntity->setMachineName($transition["machineName"]);
            $transitionEntity->setStartState($stateDictionary[$transition["startStateMachineName"]]);
            $transitionEntity->setEndState($stateDictionary[$transition["endStateMachineName"]]);

            $workflow->addTransition($transitionEntity);
        }

        $workflow->setCourse($course);

        $this->em->persist($workflow);
        $this->em->flush();
    }
}