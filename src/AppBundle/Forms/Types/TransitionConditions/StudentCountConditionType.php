<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Forms\Types\TransitionConditions;


use AppBundle\Entity\StudentCountCondition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentCountConditionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operator', ChoiceType::class, array(
                'label' => "Accepter si le nombre d'étudiants",
                'choices'  => array(
                    'Est supérieur >' => '>',
                    'Est inférieur <' => '<',
                    'Est égal =' => '==',
                    'Est différent !=' => '==',
                )))
            ->add('number', IntegerType::class ,array('label' => "Nombre d'étudiants"));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => StudentCountCondition::class
        ));
    }
}