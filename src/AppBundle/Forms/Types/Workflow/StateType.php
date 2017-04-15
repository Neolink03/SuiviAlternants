<?php
namespace AppBundle\Forms\Types\Workflow;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Created by Antoine Lamirault.
 */
class StateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => "Nom de l'état"
            ))
            ->add('machineName', TextType::class, array(
                'label' => "Nom du machine name"
            ));
    }
}