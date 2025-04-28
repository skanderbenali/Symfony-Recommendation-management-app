<?php

namespace App\Form;

use App\Entity\Review;
use App\Entity\Conseil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idConseil', EntityType::class, [
                'class' => Conseil::class,
                'disabled' => true, 
                'choice_label' => 'nomConseil',
                'attr' => ['class' => 'form-control', 'style' => 'width: 450px; display:none;'],
            ])

        ->add('title', TextType::class, [
            'label' => 'Your Title *',
            'attr' => [
                'class' => 'form-control border-0 me-4',
                'placeholder' => 'Your Title *'
            ]
        ])
        ->add('comments', TextareaType::class, [
            'label' => 'Your Comments *',
            'attr' => [
                'class' => 'form-control border-0',
                'cols' => '30',
                'rows' => '8',
                'placeholder' => 'Your Comments *',
                'spellcheck' => 'false'
            ]
        ])
       ->add('value', HiddenType::class, [
                'attr' => [
                    'class' => 'rating-input', // Assuming you will use JavaScript to set this field
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
