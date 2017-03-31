<?php
/**
 * Created by Antoine Lamirault.
 */
namespace AppBundle\DataFixtures\ORM;
namespace AppBundle\DataFixtures\ORM;
use AppBundle\Entity\Course;
use AppBundle\Entity\Promotion;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class AppFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

//        $course = new Course();
//        $course->setName('lol');
//        $course->setStudentNumber(24);
//        $course->setSecretariatContactDetails('r');
//
//        $promotion = new Promotion();
//        $promotion->setCourse($course);
//        $promotion->setName('2016-2017');
//        $promotion->setStudentNumber(24);
//        $promotion->setStartDate(new \DateTime());
//        $promotion->setEndDate(new \DateTime());
//
//        $manager->persist($promotion);
//        $manager->flush();

        Fixtures::load(
            [
                __DIR__ . '/companyContacts.yml',
                __DIR__ . '/users.yml',
                __DIR__ . '/courses.yml',
                __DIR__ . '/promotions.yml'
            ],
            $manager,
            [
                'providers' => [$this],
                'locale' => 'fr_FR'
            ]
        );
    }
}