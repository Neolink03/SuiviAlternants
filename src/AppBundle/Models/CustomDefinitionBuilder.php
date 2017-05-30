<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Models;


class CustomDefinitionBuilder
{
    private $places = array();
    private $transitions = array();
    private $initialPlace;

    /**
     * @param string[]     $places
     * @param CustomTransition[] $transitions
     */
    public function __construct(array $places = array(), array $transitions = array())
    {
        $this->addPlaces($places);
        $this->addTransitions($transitions);
    }

    /**
     * @return CustomDefinition
     */
    public function build()
    {
        return new CustomDefinition($this->places, $this->transitions, $this->initialPlace);
    }

    /**
     * Clear all data in the builder.
     */
    public function reset()
    {
        $this->places = array();
        $this->transitions = array();
        $this->initialPlace = null;
    }

    public function setInitialPlace($place)
    {
        $this->initialPlace = $place;
    }

    public function addPlace($place)
    {
        if (!$this->places) {
            $this->initialPlace = $place;
        }

        $this->places[$place] = $place;
    }

    public function addPlaces(array $places)
    {
        foreach ($places as $place) {
            $this->addPlace($place);
        }
    }

    /**
     * @param CustomTransition[] $transitions
     */
    public function addTransitions(array $transitions)
    {
        foreach ($transitions as $transition) {
            $this->addTransition($transition);
        }
    }

    public function addTransition(CustomTransition $transition)
    {
        $this->transitions[] = $transition;
    }
}