<?php
/**
 * Created by Antoine Lamirault.
 */
namespace AppBundle\DataFixtures\ORM;
namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class AppFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        Fixtures::load(
            [
                __DIR__ . '/companyContacts.yml',
                __DIR__ . '/users.yml',
                __DIR__ . '/courses.yml',
                __DIR__ . '/workflow.yml'
            ],
            $manager,
            [
                'providers' => [$this],
                'locale' => 'fr_FR'
            ]
        );
    }
}