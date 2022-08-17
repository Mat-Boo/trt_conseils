<?php

namespace App\Form;

use App\Entity\JobOffer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class JobOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'offre',
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 255
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez saisir le titre de l\'offre'
                ]
            ])
            ->add('place', TextareaType::class, [
                'label' => 'Lieu où se situe l\'emploi',
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 255
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez saisir le lieu où se situe l\'emploi'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'offre',
                'required' => true,
                'constraints' => new Length([
                    'min' => 3
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez saisir la description de l\'emploi'
                ]
            ])
            ->add('salary', NumberType::class, [
                'label' => 'Salaire',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Veuillez indiquer le salaire reçu pour cet emploi'
                ]
            ])
            ->add('working_hours', TextType::class, [
                'label' => 'Horaires de travail',
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 255
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez indiquer les horaires de travail pour cet emploi'
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
            'data_class' => JobOffer::class,
        ]);
    }
}
