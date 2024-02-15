<?php

namespace App\Form;

use App\Entity\Services;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ServicesInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', EntityType::class, [
                'label' => 'Services',
                'class' => Services::class,
                'choice_label' => 'title',
                'expanded' => true,
                'mapped' => false,
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
                'label' => 'Votre email',
                'mapped' => false
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
                'label' => 'Votre téléphone',
                'mapped' => false
            ])
            ->add('subject', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le sujet'
                    ])
                ],
                'label' => 'Sujet',
                'mapped' => false,
                'data' => 'Demande de contact concernant un service'
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre message'
                    ])
                ],
                'label' => 'Message',
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Services::class,
        ]);
    }
}
