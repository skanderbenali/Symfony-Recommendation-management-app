<?php

namespace App\Form;

use App\Entity\Typeconseil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TypeConseilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomtypec')
            ->add('Enregistrer', SubmitType::class, [
                'label' => '<i class="mdi mdi-library-plus btn-icon-prepend"></i> Enregistrer', // Include icon in button label
                'label_html' => true, // Allow HTML in button label
                'attr' => [
                    'class' => 'btn btn-success btn-icon-text', // Apply classes
                    // Add other attributes as needed
                    'style' => 'position: absolute; bottom: -47px; right: 95px;', // Positioning
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Typeconseil::class,
        ]);
    }
}
