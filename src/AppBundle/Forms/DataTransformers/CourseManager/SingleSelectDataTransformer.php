<?php

namespace AppBundle\Forms\DataTransformers\CourseManager;

use AppBundle\Entity\Issue;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class SingleSelectDataTransformer implements DataTransformerInterface
{
    public function transform($courseManager)
    {
        return ['selector' => $courseManager];
    }

    public function reverseTransform($courseManagerSelector)
    {
        return $courseManagerSelector['selector'];
    }
}
