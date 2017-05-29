<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 12/05/2017
 * Time: 10:34
 */

namespace AppBundle\Forms\Types;


use Symfony\Component\Console\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddJuryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('jurys', ChoiceType::class, [
            'choices' => $options['applications'],
            'choice_label' => function($application, $key, $index) {
                /** @var Application $application */
                return strtoupper($application->getFirstName().' '.$application->getLastName());
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