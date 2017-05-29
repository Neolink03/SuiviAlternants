<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 06/04/2017
 * Time: 16:40
 */

namespace AppBundle\Forms\Types;


use AppBundle\Entity\State;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchStudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastName', TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Nom...'
            ]
        ]);
        $builder->add('firstName', TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Prénom...'
            ]
        ]);
        if($options['states']) {
            $builder->add('currentState', ChoiceType::class, [
                'choices' => $options['states'],
                'choice_label' => function (State $state) {
                    return $state->getName();
                },
                'placeholder' => 'Trier par statut...',
                'label' => false,
                'required' => false
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'states' => [],
        ));
    }
}