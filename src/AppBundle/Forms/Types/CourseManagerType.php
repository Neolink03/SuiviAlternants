<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 06/04/2017
 * Time: 17:29
 */

namespace AppBundle\Forms\Types;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;

class CourseManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phoneNumber', TextType::class, array(
                'label' => "Numéro de téléphone",
                'required' => false,
                'constraints' => array(new Regex(
                    array(
                        'pattern' => "/^((0|\\+33)[0-9]{9})?$/",
                        'message' => 'Le numéro de téléphone doit être de format 0xxxxxxxxx ou +33xxxxxxxxx',)
                ))
            ));
    }

    public function getParent(){
        return UserType::class;
    }
}