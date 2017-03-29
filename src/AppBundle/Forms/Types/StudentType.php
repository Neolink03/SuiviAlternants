<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 15:39
 */

namespace AppBundle\Forms\Types;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', AddressType::class, array('required' => false))
            ->add('phone', TextType::class, array('label' => 'Téléphone fixe', 'required' => false))
            ->add('cellphone', TextType::class, array('label' => 'Téléphone portable', 'required' => false))
            ->add('birthday', DateType::class, array('label' => 'Date de naissance', 'required' => false))
        ;
    }

    public function getParent(){
        return UserType::class;
    }
}