<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 03/04/2017
 * Time: 10:27
 */

namespace AppBundle\Forms\Types\Courses;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EditCourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('label' => 'Nom'));
        $builder->add('submit', SubmitType::class, array('label' => 'Renommer'));
    }
}