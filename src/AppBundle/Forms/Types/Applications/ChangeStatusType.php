<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Forms\Types\Applications;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('transition', ChoiceType::class, array(
            'choices'  => $options['transitions'],
            'label' => 'Changer le statut'
        ));
        $builder->add('comment', TextareaType::class, array(
            'label' => 'Commentaires',
            'required' => false
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'transitions' => array(),
        ));
    }

}