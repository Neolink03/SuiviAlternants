<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 03/04/2017
 * Time: 10:02
 */

namespace AppBundle\Services\Factories;

use AppBundle\Entity\Course;
use AppBundle\Entity\Promotion;
use Doctrine\ORM\EntityManager;

class PromotionFactory
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createPromotionFromForm(int $courseId, array $data)
    {
        $course = $this->em->getRepository(Course::class)->find($courseId);
        $promotion = new Promotion();

        $promotion->setCourse($course);
        $promotion->setName($data['name']);
        $promotion->setStudentNumber($data['studentNumber']);

        $startDate = \DateTime::createFromFormat('d-m-Y', implode('-', $data['startDate']));
        $promotion->setStartDate($startDate);

        $endDate = \DateTime::createFromFormat('d-m-Y', implode('-', $data['endDate']));
        $promotion->setEndDate($endDate);

        $this->em->persist($promotion);
        $this->em->flush();
    }

}