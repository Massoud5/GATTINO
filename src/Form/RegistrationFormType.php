<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control form-control-lg'],
                'label' => 'Prénom',
                'invalid_message' => 'Entrez votre prénom svp!',
            ])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control form-control-lg'],
                'label' => 'NOM',
                'invalid_message' => 'Entrez votre nom svp!',
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control form-control-lg',
                'placeholder' => 'exemple@exemple.com',
                'pattern' => '^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$'
                ],
                'label' => 'Email',
                'invalid_message' => 'Entrez un mail valid svp!',
            ])
            ->add('tel', TelType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'pattern' => '^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$',
                    'placeholder' => '0612345678',
                ],
                'label' => 'Tel',
                'invalid_message' => 'Entrez un numéro de téléphone valid svp!',
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent être identique.',
                'options' => [
                    'attr' => [
                        'class' => 'form-control form-control-lg',
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répéter mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mots de passe valid svp!',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Votre mots de passe dois avoir au moins {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'enregistrer',
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success',
                ],
                'label' => 'CRÉER'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
