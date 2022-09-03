<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class RegisterCandidateType extends AbstractType
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
                ]);
        if (substr($_SERVER['REQUEST_URI'], 0, 12) === '/inscription') {
            $builder->add('password', RepeatedType::class, [
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
            ]);
        }
        $builder->add('firstname', TextType::class, [
                'label' => 'Votre Prénom',
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 255
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 255
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre nom'
                ]
            ])
            ->add('job', TextType::class, [
                'label' => 'Votre emploi recherché',
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 255
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre emploi'
                ]
                ])
            ->add('cv', FileType::class, [
                'label' => 'Votre CV au format PDF exclusivement (Max 5Mo)',
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '5120k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Seul le format PDF est accepté.',
                    ])
                ],
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
