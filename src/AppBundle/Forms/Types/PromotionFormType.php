<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Forms\Types;


use AppBundle\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('promotions', EntityType::class, [
            'class' => Promotion::class,
            'choices' => $options['promotions'],
            'choice_label' => function (Promotion $promotion) {
                return $promotion->getName();
            },
            'label' => 'Promotion'
        ])
            ->add('submit', SubmitType::class, ['label' => 'Choisir']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'promotions' => [],
        ));
    }
}