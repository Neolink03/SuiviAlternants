<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 09:20
 */

namespace AppBundle\Forms\Types;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', TextType::class, array('label' => 'Adresse'))
            ->add('city', TextType::class, array('label' => 'Ville'))
            ->add('postalCode', IntegerType::class, array('label' => 'Code Postal'))
            ->add('country', TextType::class, array('label' => 'Pays'))
        ;
    }
}