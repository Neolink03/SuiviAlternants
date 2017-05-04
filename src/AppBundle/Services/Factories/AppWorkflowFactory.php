<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services\Factories;


use AppBundle\Entity\Course;
use AppBundle\Entity\Promotion;
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

    public function createAppWorflowFromYml(Promotion $promotion, string $workflowYmlPath){
        $stateDictionary = [];
        list($states, $transitions) =array_values(Yaml::parse(file_get_contents($workflowYmlPath)));

        $workflow = new WorkFlow();

        foreach ($states as $state){
            $stateEntity = new State();
            $stateEntity->setName($state["name"]);
            $workflow->addState($stateEntity);
            $stateDictionary[$state["name"]] = $stateEntity;
        }

        foreach ($transitions as $transition){
            $transitionEntity = new Transition();
            $transitionEntity->setName($transition["name"]);
            $transitionEntity->setStartState($stateDictionary[$transition["startStateName"]["name"]]);
            $transitionEntity->setEndState($stateDictionary[$transition["endStateName"]["name"]]);
            $workflow->addTransition($transitionEntity);
        }
        $workflow->setPromotion($promotion);

        $this->em->persist($workflow);
        $this->em->flush();
    }
}