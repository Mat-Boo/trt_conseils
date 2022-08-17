<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterRecruiterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'required' => true,
                'constraints' => new Length([
                    'min' => 6,
                    'max' => 255
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre email'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'label' => 'Votre mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'Votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez saisir votre mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez confirmer votre mot de passe'
                    ]
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Votre nom de société',
                'required' => true,
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 255
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre nom de société'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse de votre société',
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 255
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez saisir l\'adresse de votre société'
                ]
            ])
            /* ->add('roles', HiddenType::class, [
                'empty_data' => 'ROLE_RECRUITER'
            ]) */
            ->add('submit', SubmitType::class, [
                'label' => 'Créer mon compte'
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
