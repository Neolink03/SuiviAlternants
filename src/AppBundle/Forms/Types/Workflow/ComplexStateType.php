<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Forms\Types\Workflow;


use AppBundle\Entity\State;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComplexStateType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => "Nom de l'état",
                'data' => $options['stateName']
            ));
        $builder->add('trigger', ChoiceType::class, [
            'required' => false,
            'choices' => $options['triggersAviable'],
            'label' => "Etat initial",
            'data' =>  $options['triggersSelected']
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'stateName' => '',
            'triggersSelected' => '',
            'triggersAviable' => [],
        ));
    }
}