<?php

namespace AppBundle\Forms\Types\Courses;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Forms\Types\CourseManagers\CourseManagerSingleSelectType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Forms\DataTransformers\CourseManager\SingleSelectDataTransformer as CourseManagerSingleSelectDataTransformer;

class CourseCreateType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Fields
        $builder
            ->add('name', TextType::class, [
                 'label' => 'Nom'
            ])
            ->add('manager', CourseManagerSingleSelectType::class, [
                 'label' => 'Responsable de formation'
            ])
            ->add('coManager', CourseManagerSingleSelectType::class, [
                 'label' => 'Co-responsable de formation',
                 'required' => false
            ])
            ->add('secretariatContactDetails', TextareaType::class, [
                'label' => 'Coordonnées secrétariat'
            ])
            ->add('submit', SubmitType::class, ['label' => 'Valider'])
        ;
        
        // Data transformers
        $builder->get('manager')->addModelTransformer(new CourseManagerSingleSelectDataTransformer());
        $builder->get('coManager')->addModelTransformer(new CourseManagerSingleSelectDataTransformer());
    }
}
