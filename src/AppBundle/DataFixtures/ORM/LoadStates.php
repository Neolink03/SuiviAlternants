<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\DataFixtures\ORM;
use AppBundle\Entity\State;
use AppBundle\Entity\Transition;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadStates extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $states = array(
            'creation' => 'Création',
            'complet' => 'Complet',
            'incomplet' => 'Incomplet',
            'attenteEtude' => 'En attende etude',
            'refuse' => 'Refusé',
            'admissible' => 'Admissible',
        );

        foreach ($states as $stateMachineName => $stateName) {
            $state = new State();
            $state->setMachineName($stateMachineName);
            $state->setName($stateName);

            $manager->persist($state);
            $this->addReference($stateMachineName, $state);
        }

        $transition = new Transition();
        $transition->setMachineName('dossierComplet');
        $transition->setName('Dossier complet');
        $transition->setStartState($this->getReference("creation"));
        $transition->setEndState($this->getReference("complet"));
        $manager->persist($transition);

        $transition = new Transition();
        $transition->setMachineName('dossierIncomplet');
        $transition->setName('Dossier Incomplet');
        $transition->setStartState($this->getReference("creation"));
        $transition->setEndState($this->getReference("incomplet"));
        $manager->persist($transition);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}