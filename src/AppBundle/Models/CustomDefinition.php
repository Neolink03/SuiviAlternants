<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Models;


use LogicException;
use Symfony\Component\Workflow\Transition;

class CustomDefinition
{
    private $places = array();
    private $transitions = array();
    private $initialPlace;

    /**
     * @param string[]     $places
     * @param string|null  $initialPlace
     */
    public function __construct(array $places, array $transitions, $initialPlace = null)
    {
        foreach ($places as $place) {
            $this->addPlace($place);
        }

        foreach ($transitions as $transition) {
            $this->addTransition($transition);
        }

        $this->setInitialPlace($initialPlace);
    }

    /**
     * @return string|null
     */
    public function getInitialPlace()
    {
        return $this->initialPlace;
    }

    /**
     * @return string[]
     */
    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * @return CustomTransition[]
     */
    public function getTransitions()
    {
        return $this->transitions;
    }

    private function setInitialPlace($place)
    {
        if (null === $place) {
            return;
        }

        if (!isset($this->places[$place])) {
            throw new LogicException(sprintf('Place "%s" cannot be the initial place as it does not exist.', $place));
        }

        $this->initialPlace = $place;
    }

    private function addPlace($place)
    {
        if (!count($this->places)) {
            $this->initialPlace = $place;
        }

        $this->places[$place] = $place;
    }

    private function addTransition(CustomTransition $transition)
    {
        $name = $transition->getName();
        foreach ($transition->getFroms() as $from) {
            if (!isset($this->places[$from])) {
                throw new LogicException(sprintf('Place "%s" referenced in transition "%s" does not exist.', $from, $name));
            }
        }

        foreach ($transition->getTos() as $to) {
            if (!isset($this->places[$to])) {
                throw new LogicException(sprintf('Place "%s" referenced in transition "%s" does not exist.', $to, $name));
            }
        }

        $this->transitions[] = $transition;
    }
}