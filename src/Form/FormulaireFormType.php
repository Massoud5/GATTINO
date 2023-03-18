<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class FormulaireFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        date_default_timezone_set('Europe/Paris');
        $hour = date('H');
        
        // conditionner les jours de commande produits de catégorie
        if($hour >= 00  && $hour <= 12){
            $date = (new \DateTime())->modify("+0 hours")->format('Y-m-d');
        }
        elseif($hour >= 13  && $hour <= 23){
            $date = (new \DateTime())->modify("+24 hours")->format('Y-m-d');
        }

        $builder
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'value' => 'Massoud'
                ],
                'label' => 'Prénom*',
                'invalid_message' => 'Entrez votre prénom',
            ])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control',
                'value' => 'SHAMS'
            ],
                'label' => 'NOM*',
                'invalid_message' => 'Entrez votre nom',
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'exemple@exemple.com',
                    'pattern' => '^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$',
                    'value' => 'massoud.shams@gmail.com'
                ],
                'label' => 'Email*',
                'invalid_message' => 'Entrez un mail valid',
            ])
            ->add('tel', TelType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'pattern' => '^(?:(?:(?:\+|00)33[ ]?(?:\(0\)[ ]?)?)|0){1}[1-9]{1}([ .-]?)(?:\d{2}\1?){3}\d{2}$',
                    'placeholder' => '0612345678',
                    'value' => '0612345678'
                ],
                'label' => 'Téléphone*',
                'invalid_message' => 'Entrez un numéro de téléphone valid',
                ])
            ->add('raisonSociale', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'formulaire_raisonSociale',
                ],
                'label' => 'Raison sociale(obligatoire pour entreprise)',
                'required' => false,
                'invalid_message' => 'Entrez votre raison sociale',
            ])
            ->add('adresse', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                'placeholder' => "1, rue d'example",
                ],
                'label' => 'Adresse(obligatoire pour entreprise)',
                'required' => false,
                'invalid_message' => 'Entrez une adresse valid',
            ])
            ->add('cp', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'pattern' => "^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$"
                ],
                'label' => 'Code postal(obligatoire pour entreprise)',
                'required' => false,
                'invalid_message' => 'Entrez un code postal valid',
            ])
            ->add('ville', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Ville(obligatoire pour entreprise)',
                'required' => false,
                'invalid_message' => 'Entrez votre ville',
            ])
            ->add('dateRetrait', DateType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => $date,
                ],
                'label' => 'Choisir la date de retrait',
                'widget' => 'single_text',
                'invalid_message' => 'Entrez une date de retrait valid',
            ])
            ->add('tempsRetrait', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => "Choisir le temps de retrait",
                'choices' => [
                    'Matin' => 'Matin',
                    'Après-midi' => 'Après-midi',
                ],
                'invalid_message' => 'Choisissez une plage horaire valide',
            ])
            ->add('dateRetraitPA', DateType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => (new \DateTime())->modify("+24 hours")->format('Y-m-d')
                ],
                'label' => 'Choisir la date de retrait',
                'widget' => 'single_text',
                'invalid_message' => 'Entrez une date de retrait valide',
            ])
            ->add('tempsRetraitPA', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Choisir le temps de retrait',
                'choices' => [
                    'Matin' => 'Matin',
                    'Après-midi' => 'Après-midi',
                ],
                'invalid_message' => 'Choisissez une option valide',
            ])
            // ->add('captcha', Recaptcha3Type::class, [
            //     'constraints' => new Recaptcha3(),
            //     'action_name' => 'formulaire',
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
            'data_class' => Client::class,
        ]);
    }
}