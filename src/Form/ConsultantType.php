<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ConsultantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints' => new Length([
                    'min' => 6,
                    'max' => 255
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez saisir l\'email du consultant'
                ]
                ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'label' => 'Mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez définir un mot de passe pour le consultant'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez ce mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez confirmer ce mot de passe'
                    ]
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 255
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez saisir le prénom du consultant'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 255
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez saisir le nom du consultant'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
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
