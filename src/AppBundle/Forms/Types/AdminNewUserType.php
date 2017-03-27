<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Forms\Types;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

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
        ));
        $builder->add('user', UserType::class);
        $builder->add('phoneNumber', TextType::class, array(
            'label' => "Numéro de téléphone",
            'required' => false
        ));
    }
}