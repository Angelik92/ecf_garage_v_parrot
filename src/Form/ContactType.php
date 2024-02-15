<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('service', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Achat' => 'Achat de véhicule',
                    'Vente' => 'Vendre son véhicule',
                    'Atelier' => 'Réparation',
                    'Autre' => 'Autre'
                ],
                'placeholder' => 'Veuillez renseigner le type de service',
            ])
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre prénom'
                    ])
                ],
                'label' => 'Prénom',
                'mapped' => false
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom'
                    ])
                ],
                'label' => 'Nom',
                'mapped' => false
            ])
            ->add('customerEmail', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre email'
                    ])
                ],
                'label' => 'Email',
            ])
            ->add('phoneNumber', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre téléphone'
                    ]),
                    new Regex(
                        pattern: '/^0(?!8)\d{9}$/i',
                        message: 'Vous ne pouvez utiliser que des chiffres'

                    )
                ],
                'label' => 'Téléphone',
            ])
            ->add('subject', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le sujet'
                    ])
                ],
                'label' => 'Sujet',
                'data' => 'Demande de contact concernant un service'
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre message'
                    ])
                ],
                'label' => 'Message',
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
