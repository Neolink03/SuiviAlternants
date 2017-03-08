<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Course;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCourses extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $courses = array(
            'Metinet' => 24,
            'IEM' => 21,
            "Bio mecanique des fluides" => 13
        );

        foreach ($courses as $courseName => $number) {
            $course = new Course();
            $course->setName($courseName);
            $course->setStudentNumber($number);
            $manager->persist($course);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}
