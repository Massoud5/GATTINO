<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Service;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ProduitFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProduit', TextType::class, [
                'label' => "Nom du produit* (sans accents, sans espaces et sans apostrophe)",
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom-de-produit',
                    'pattern' => "^[-a-zA-Z0-9@\.+_]+$",
                ],
                'invalid_message' => 'Entrez un nom de produit valid',
            ])
            ->add('titre', TextType::class, [
                'label' => 'Titre produit*',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de produit',
                ],
                'invalid_message' => 'Entrez un titre produit',
            ])
            ->add('marque', TextType::class, [
                'label' => 'Marque de produit',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de la marque',
                ],
                'invalid_message' => 'Entrez la marque',
            ])
            ->add('reference', TextType::class, [
                'label' => "Référence (chiffres et/ou lettres sans espaces)",
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'pattern' => "^[-a-zA-Z0-9@\.+_]+$",
                    'placeholder' => 'A123456789-1b',
                ],
                'invalid_message' => 'Entrez un référence valid',
            ])
            ->add('image', FileType::class, [
                'label' => "Image JPG/JPEG* (nommer l'image sans espace, sans accent et sans apostrophe)",
                // unmapped means that this field is not associated to any entity property
                // pour ne pas ajouter le fichier dans db mais justement un string du path et ensuite uploader le fichier image dans dossier d'application
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Téléchargez une image valid',
                    ])
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'label' => 'Catégorie',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'invalid_message' => 'Choisissez la catégorie',
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'label' => 'Service',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'invalid_message' => 'Choisissez le service',
            ])
            ->add('uniteMesure', ChoiceType::class, [
                'label' => 'Unité de mesure',
                'required' => false,
                'attr' => [
                    'class' => 'form-control ',
                ],
                'choices' => [
                    'g (gramme)' => true,
                    'mL (mili-litre)' => false,
                ],
                'invalid_message' => 'Choisissez g ou L',
            ])
            ->add('poidsVolumeUnitaire', NumberType::class, [
                'label' => "Masse ou volume unitaire (en g ou mL)",
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'pattern' => "^[0-9]+$",
                    'placeholder' => '100'
                ],
                'invalid_message' => 'Entrez un prix valid',
            ])
            ->add('prixAuKiloLitre', NumberType::class, [
                'label' => "Prix au kilo ou au litre (utiliser << . >> au lieu de << , >>)",
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'pattern' => "^[0-9.]+$",
                    'placeholder' => '10.00'
                ],
                'invalid_message' => 'Entrez un prix valid',
            ])
            ->add('prixUnitaire', NumberType::class, [
                'label' => "Prix unitaire (TTC)* (utiliser << . >> au lieu de << , >>)",
                'attr' => [
                    'class' => 'form-control',
                    'pattern' => "^[0-9.]+$",
                    'placeholder' => '10.00'
                ],
                'invalid_message' => 'Entrez un prix valid',
            ])
            ->add('taxe', NumberType::class, [
                'label' => "Taxe (taxe/100, utiliser << . >> au lieu de << , >>)",
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'pattern' => "^[0-9.]+$",
                    'placeholder' => '0.2 (pour 20%)'
                ],
                'invalid_message' => 'Entrez un chiffre valid',
            ])
            // ->add('promotion')
            ->add('produitAlcoolique', ChoiceType::class, [
                'label' => 'Produit alcoolique?*',
                'attr' => [
                    'class' => 'form-control ',
                ],
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
                'invalid_message' => 'Choisissez oui ou non',
            ])
            ->add('commandable', ChoiceType::class, [
                'label' => 'commandable?*',
                'attr' => [
                    'class' => 'form-control ',
                ],
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'invalid_message' => 'Choisissez oui ou non',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description*',
                'attr' => [
                    'class' => 'form-control',
                ],
                'invalid_message' => 'Entrez une description de la catégorie',
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'produit',
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success',
                ],
                'label' => 'CONFIRMER',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
