<?php

namespace App\Form;

use App\Entity\Typeconseil;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConseilFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomConseil', Filters\TextFilterType::class, [
                'label' => 'Name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Search by name',
                ],
                'required' => false,
                'condition_pattern' => 2, // 2 = CONTAINS
            ])
            ->add('idTypec', Filters\EntityFilterType::class, [
                'label' => 'Category',
                'class' => Typeconseil::class,
                'choice_label' => 'nomtypec',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'placeholder' => 'All Categories',
            ])
            ->add('datecreation', Filters\DateRangeFilterType::class, [
                'label' => 'Date Range',
                'left_date_options' => [
                    'label' => 'From',
                    'widget' => 'single_text',
                    'attr' => ['class' => 'form-control'],
                ],
                'right_date_options' => [
                    'label' => 'To',
                    'widget' => 'single_text',
                    'attr' => ['class' => 'form-control'],
                ],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Apply Filters',
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ]
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'conseil_filter';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'validation_groups' => ['filtering'],
            'method' => 'GET',
        ]);
    }
}
