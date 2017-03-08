<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\User\Administrator;
use AppBundle\Entity\User\CourseManager;
use AppBundle\Entity\User\Student;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUsers extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $admin = new Administrator();
        $admin->setFirstName('John');
        $admin->setLastName('Doe');
        $admin->setUsername('admin');
        $admin->setPassword('admin');
        $admin->setEmail('test@test.test');

        $manager->persist($admin);

        $courseManager = new CourseManager();
        $courseManager->setFirstName('Adrien');
        $courseManager->setLastName('Dupond');
        $courseManager->setEmail('test@test.test');
        $courseManager->setUsername('manage');
        $courseManager->setPassword('manage');
        $courseManager->setPhoneNumber('0245986552');

        $manager->persist($courseManager);

        $student = new Student();
        $student->setFirstName('Antoine');
        $student->setLastName('Joubert');
        $student->setEmail('test@test.test');
        $student->setUsername('student');
        $student->setPassword('student');

        $manager->persist($student);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}