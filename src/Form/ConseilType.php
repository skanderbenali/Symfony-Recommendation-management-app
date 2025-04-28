<?php

namespace App\Form;

use App\Entity\Conseil;
use App\Entity\Typeconseil;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\MimeType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\Length;


class ConseilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nomConseil', null, [
            'label' => 'Nom Conseil',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Entrer nom conseil'],
            'constraints' => [
                new Callback([$this, 'validateStartsWithUppercase']),
            ],
        ])
            ->add('video', FileType::class, [
                'required' => false,
                'mapped' => false, 
                'label' => 'Video',
                'constraints' => [
                    new File([
                        'maxSize' => '5000000k',
                        'mimeTypes' => ['video/mp4'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier vidéo MP4 valide',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control-file',
                ],
            ])
            ->add('description', null, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrer description'],
                'constraints' => [
                    new Length([
                        'min' => 20,
                        'minMessage' => "Le champ 'description' doit avoir au moins {{ limit }} caractères",
                        'max' => 255,
                        'maxMessage' => "Le champ 'description' ne peut pas dépasser {{ limit }} caractères",
                    ]),
                ],
            ])
            ->add('idTypec', EntityType::class, [
                'label' => 'Selectionner Categorie',
                'class' => Typeconseil::class,
                'choice_label' => 'nomtypec',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('idProduit', EntityType::class, [
                'label' => 'Selectionner Produit',
                'class' => Produit::class,
                'choice_label' => 'nomProduit',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Enregistrer', SubmitType::class, [
                'label' => '<i class="mdi mdi-content-save"></i> Save',
                'label_html' => true, // Allow HTML in button label
                'attr' => [
                    'class' => 'btn btn-success btn-icon-text', // Apply classes
                ]
            ]);
    }
    
    public function validateStartsWithUppercase($value, ExecutionContextInterface $context) // classe najmu ndetectou bih est ce que fama violation wele
{
    if ($value !== null && !preg_match('/^[A-Z]/', $value)) {
        $context->buildViolation("Le champ doit commencer par une lettre majuscule.")
            ->addViolation();
    }}
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conseil::class,
        ]);
    }
}