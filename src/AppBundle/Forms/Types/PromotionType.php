<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 31/03/2017
 * Time: 10:27
 */

namespace AppBundle\Forms\Types;

use AppBundle\Entity\Promotion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('label' => 'Nom'));
        $builder->add('startDate', DateType::class, array('label' => 'Date de début'));
        $builder->add('endDate', DateType::class, array('label' => 'Date de fin'));
        $builder->add('studentNumber', NumberType::class, array('label' => 'Nombre d\'étudiants maximal'));
        $builder->add('submit', SubmitType::class, array('label' => 'Ajouter'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Promotion::class,
        ));
    }
}