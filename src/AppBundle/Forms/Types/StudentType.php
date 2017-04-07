<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 15:39
 */

namespace AppBundle\Forms\Types;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', AddressType::class, array('required' => false))
            ->add('phone', TextType::class, array(
                'label' => 'Téléphone fixe',
                'required' => false,
                'constraints' => array(new Regex(
                    array(
                        'pattern' => "/^((0|\\(\\+33\\))[0-9]{9})?$/",
                        'message' => 'Le numéro de téléphone doit être de format 0xxxxxxxxx ou (+33)xxxxxxxxx',)
                ))
            ))
            ->add('cellphone', TextType::class, array(
                'label' => 'Téléphone portable',
                'required' => false,
                'constraints' => array(new Regex(
                    array(
                        'pattern' => "/^(0|\\(\\+33\\))[0-9]{9}$/",
                        'message' => 'Le numéro de téléphone doit être de format 0xxxxxxxxx ou (+33)xxxxxxxxx',)
                ))
            ))
            ->add('birthday', BirthdayType::class, array('label' => 'Date de naissance', 'required' => false))
            ->add('professionnalSocialNetworkLink', TextType::class, array('label' => 'Lien réseau social professionnel', 'required' => false))
        ;
    }

    public function getParent(){
        return UserType::class;
    }
}