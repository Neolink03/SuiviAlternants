<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Models;


use Symfony\Component\Workflow\Marking;

class CustomGraphvizDumper
{
    protected static $defaultOptions = array(
        'graph' => array('ratio' => 'compress', 'rankdir' => 'LR'),
        'node' => array('fontsize' => 9, 'fontname' => 'Arial', 'color' => '#333333', 'fillcolor' => 'lightblue', 'fixedsize' => true, 'width' => 1),
        'edge' => array('fontsize' => 9, 'fontname' => 'Arial', 'color' => '#333333', 'arrowhead' => 'normal', 'arrowsize' => 0.5),
    );

    /**
     * {@inheritdoc}
     *
     * Dumps the workflow as a graphviz graph.
     *
     * Available options:
     *
     *  * graph: The default options for the whole graph
     *  * node: The default options for nodes (places + transitions)
     *  * edge: The default options for edges
     */
    public function dump(CustomDefinition $definition, Marking $marking = null, array $options = array())
    {
        $places = $this->findPlaces($definition, $marking);
        $edges = $this->findEdges($definition);

        $options = array_replace_recursive(self::$defaultOptions, $options);

        return $this->startDot($options)
            .$this->addPlaces($places)
            .$this->addEdges($edges)
            .$this->endDot()
            ;
    }

    /**
     * @internal
     */
    protected function findPlaces(CustomDefinition $definition, Marking $marking = null)
    {
        $places = array();

        foreach ($definition->getPlaces() as $place) {
            $attributes = array();
            if ($place === $definition->getInitialPlace()) {
                $attributes['style'] = 'filled';
            }
            if ($marking && $marking->has($place)) {
                $attributes['color'] = '#FF0000';
                $attributes['shape'] = 'doublecircle';
            }
            $places[$place] = array(
                'attributes' => $attributes,
            );
        }

        return $places;
    }

    /**
     * @internal
     */
    protected function findTransitions(CustomDefinition $definition)
    {
        $transitions = array();

        foreach ($definition->getTransitions() as $transition) {
            $transitions[] = array(
                'attributes' => array('shape' => 'box', 'regular' => true),
                'name' => $transition->getName(),
            );
        }

        return $transitions;
    }

    /**
     * @internal
     */
    protected function addPlaces(array $places)
    {
        $code = '';

        foreach ($places as $id => $place) {
            $code .= sprintf("  place_%s [label=\"%s\", shape=circle%s];\n", $this->dotize($id), $id, $this->addAttributes($place['attributes']));
        }

        return $code;
    }

    /**
     * @internal
     */
    protected function addTransitions(array $transitions)
    {
        $code = '';

        foreach ($transitions as $place) {
            $code .= sprintf("  transition_%s [label=\"%s\", shape=box%s];\n", $this->dotize($place['name']), $place['name'], $this->addAttributes($place['attributes']));
        }

        return $code;
    }

    /**
     * @internal
     */
    protected function findEdges(CustomDefinition $definition)
    {
        $edges = array();

        foreach ($definition->getTransitions() as $transition) {
            foreach ($transition->getFroms() as $from) {
                foreach ($transition->getTos() as $to) {
                    $edges[$from][] = array(
                        'name' => $transition->getName(),
                        'to' => $to,
                    );
                }
            }
        }

        return $edges;
    }

    /**
     * @internal
     */
    protected function addEdges(array $edges)
    {
        $code = '';

        foreach ($edges as $id => $edges) {
            foreach ($edges as $edge) {
                $code .= sprintf("  place_%s -> place_%s [label=\"%s\" style=\"%s\"];\n", $this->dotize($id), $this->dotize($edge['to']), $edge['name'], 'solid');
            }
        }

        return $code;
    }

    /**
     * @internal
     */
    protected function startDot(array $options)
    {
        return sprintf("digraph workflow {\n  %s\n  node [%s];\n  edge [%s];\n\n",
            $this->addOptions($options['graph']),
            $this->addOptions($options['node']),
            $this->addOptions($options['edge'])
        );
    }

    /**
     * @internal
     */
    protected function endDot()
    {
        return "}\n";
    }

    /**
     * @internal
     */
    protected function dotize($id)
    {
        return preg_replace('/[^\w]/i', '_', $id);
    }

    private function addAttributes(array $attributes)
    {
        $code = array();

        foreach ($attributes as $k => $v) {
            $code[] = sprintf('%s="%s"', $k, $v);
        }

        return $code ? ', '.implode(', ', $code) : '';
    }

    private function addOptions(array $options)
    {
        $code = array();

        foreach ($options as $k => $v) {
            $code[] = sprintf('%s="%s"', $k, $v);
        }

        return implode(' ', $code);
    }
}
