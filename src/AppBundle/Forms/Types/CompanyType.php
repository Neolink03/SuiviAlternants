<?php

namespace AppBundle\Forms\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom",
                'disabled' => $options['disabled']
            ])
            ->add('address', AddressType::class, [
                'label' => false,
                'disabled' => $options['disabled']
            ])
            ->add('employeeNumber', IntegerType::class, [
                'label' => "Nombre de salariés",
                'disabled' => $options['disabled']
            ])
            ->add('itNumber', IntegerType::class, [
                'label' => "Nombre d'informaticiens",
                'disabled' => $options['disabled']
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Company',
            'disabled' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_company';
    }


}
