<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 01/06/2017
 * Time: 10:15
 */

namespace AppBundle\Forms\Types;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AfterCourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('job', TextType::class, array(
                'label' => "Veuillez indiquer ce que vous faites (métier, chômage, reconversion...)",
                'disabled' => $options['disabled']
            ))
            ->add('wage', IntegerType::class, array(
                'label' => 'Votre salaire',
                'required' => false,
                'disabled' => $options['disabled']
            ))
            ->add('company', TextType::class, array(
                'label' => "Votre entreprise",
                'required' => false,
                'disabled' => $options['disabled']
            ))
            ->add('comment', TextType::class, array(
                'label' => 'Informations complémentaires : comment avez-vous trouvé votre entreprise, si reconversion pourquoi ce choix? ...',
                'required' => false,
                'disabled' => $options['disabled']
            ));
    }
}