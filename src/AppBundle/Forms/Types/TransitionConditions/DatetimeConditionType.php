<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Forms\Types\TransitionConditions;


use AppBundle\Entity\DatetimeCondition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatetimeConditionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operator', ChoiceType::class, array(
                'label' => "Condition sur la date",
                'choices'  => array(
                    'Avant le' => '<',
                    'Apres le' => '>'
                )))
            ->add('datetime', DateType::class ,array('label' => "Date"));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => DatetimeCondition::class
        ));
    }
}