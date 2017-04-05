<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 11:08
 */

namespace AppBundle\Forms\Types;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, array(
            'label' => "Prénom",
            'disabled' => 'true'
        ));
        $builder->add('lastName', TextType::class, array(
            'label' => "Nom",
            'disabled' => 'true'
        ));
        $builder->add('email', EmailType::class, array(
            'label' => "E-mail",
            'disabled' => 'true'
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }

}