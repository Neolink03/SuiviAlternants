<?php

namespace AppBundle\Forms\Types\Courses;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Forms\Types\CourseManagers\CourseManagerSingleSelectType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CourseType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                 'label' => 'Nom'
            ])
            ->add('manager', CourseManagerSingleSelectType::class, [
                 'label' => 'Responsable de formation'
            ])
            ->add('coManager', CourseManagerSingleSelectType::class, [
                 'label' => 'Co-responsable de formation'
            ])
            ->add('studentNumber', NumberType::class, [
                'label' => "Nombre d'étudiants"
            ])
            ->add('secretariatContactDetails', TextareaType::class, [
                'label' => 'Coordonnées secrétariat'
            ]);
    }
    
}
