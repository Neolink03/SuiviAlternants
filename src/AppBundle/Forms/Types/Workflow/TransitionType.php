<?php
/**
 * Created by Antoine Lamirault.
 */

namespace AppBundle\Forms\Types\Workflow;


use AppBundle\Entity\State;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => "Nom de la transition"
            ));
        $builder->add('startState', ChoiceType::class, [
            'choices' => $options['states'],
            'choice_label' => function (State $state) {
                return $state->getName();
            },
            'label' => "Etat initial"
        ]);
        $builder->add('endState', ChoiceType::class, [
            'choices' => $options['states'],
            'choice_label' => function (State $state) {
                return $state->getName();
            },
            'label' => "Etat d'arrivé"
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'states' => [],
        ));
    }
}