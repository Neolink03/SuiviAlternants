<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Forms\Types;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;

class AdminNewUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('userType', ChoiceType::class, array(
            'choices'  => array(
                'Responsable formation' => "responsable",
                'Jury' => "jury",
            ),
            'expanded' => true,
            'label' => "Type utilisateur"
        ))
        ->add('user', UserType::class, array('label' => ' '))
        ->add('phoneNumber', TextType::class, array(
            'label' => "Numéro de téléphone",
            'required' => false,
            'constraints' => array(new Regex(
                array(
                    'pattern' => "/^((0|\\(\\+33\\))[0-9]{9})?$/",
                    'message' => 'Le numéro de téléphone doit être de format 0xxxxxxxxx ou (+33)xxxxxxxxx',)
            ))
        ));
    }
}