<?php

namespace AppBundle\Forms\Types\CourseManagers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\User\CourseManager;

class CourseManagerSingleSelectType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('selector', EntityType::class, [
            'class' => CourseManager::class,
            'choice_label' => function (CourseManager $courseManager) {
                return $courseManager->getFullName();
            },
            'placeholder' => ""
        ])
        ;
    }
}
