<?php

namespace App\Form;


use App\Entity\Ads;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdsContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('firstname', TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre prénom'
                    ])
                ],
                'label'=>'Prénom',
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
                'label'=>'Votre email',
                'mapped' => false
            ])
            ->add('phoneNumber', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre téléphone'
                    ])
                ],
                'label' => 'Votre téléphone',
                'mapped' => false
            ])
            ->add('subject', TextType::class, ['constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir le sujet'
                ])
            ],
                'label' => 'Sujet',
                'mapped' => false,
                'data' => 'Demande de contact concernant une annonce'
            ])
            ->add('comment', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre commentaire'
                    ])
                ],
                'label' => 'Commentaire',
                'mapped' => false
            ])
            ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ads::class,
        ]);
    }
}