<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Forms\Types;


use AppBundle\Entity\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('object', TextType::class,array(
            'label' => "Objet du message"
        ));
        $builder->add('message', TextareaType::class, array(
            'label' => "Message"
        ));
        $builder->add('users', ChoiceType::class, [
            'choices' => $options['applications'],
            'choice_label' => function($application, $key, $index) {
                /** @var Application $application */
                return strtoupper($application->getStudent()->getFirstName().' '.$application->getStudent()->getLastName());
            },
            'expanded' => true,
            'multiple' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'applications' => [],
        ));
    }
}