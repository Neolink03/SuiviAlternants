<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 04/05/2017
 * Time: 16:59
 */

namespace AppBundle\Forms\Types;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*
            ->add('password', PasswordType::class, array('label' => 'Nouveau mot de passe'))
            ->add('confirmation', PasswordType::class, array('label' => 'Confirmation du mot de passe'))
            */
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne sont pas identiques',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Nouveau mot de passe'),
                'second_options' => array('label' => 'Confirmation du mot de passe'),
            ));
    }
}