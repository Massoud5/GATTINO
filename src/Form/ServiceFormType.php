<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ServiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom du service* (sans accents, sans espaces et sans apostrophe)",
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Produit-au-detail',
                    'pattern' => "^[-a-zA-Z0-9@\.+_]+$",
                ],
                'invalid_message' => 'Entrez un nom de service valid',
            ])
            ->add('titre', TextType::class, [
                'label' => 'Titre*',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nos produits au détails',
                ],
                'invalid_message' => 'Entrez un titre service',
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
            ->add('description', TextareaType::class, [
                'label' => 'Description courte*',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eros justo, faucibus nec semper non, gravida nec eros. Fusce nec rutrum sem, sed placerat neque.',
                ],
                'invalid_message' => 'Entrez un titre service',
            ])
            ->add('commandabilite', ChoiceType::class, [
                'label' => 'Commandabilité*',
                'attr' => [
                    'class' => 'form-control ',
                ],
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'invalid_message' => 'Choisissez oui ou non',
            ])
            // ->add('captcha', Recaptcha3Type::class, [
            //     'constraints' => new Recaptcha3(),
            //     'action_name' => 'service',
            // ])
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
            'data_class' => Service::class,
        ]);
    }
}
