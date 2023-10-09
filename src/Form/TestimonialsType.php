<?php

namespace App\Form;

use App\Entity\Services;
use App\Entity\Testimonials;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimonialsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', TextType::class,[
                'label' => 'Nom du client'
            ])
            ->add('date_of_service', DateType::class,[
                'label'=>'Date du service'
            ])
            ->add('service', EntityType::class, [
                'label' => 'Service',
                'class' => Services::class,
                'choice_label' => 'title',
                'placeholder' => 'Choisir le service'
            ])
            ->add('rating', IntegerType::class,[
                'label' => 'Note'
            ])
            ->add('content', TextareaType::class,[
                'label' => 'Commentaire',
                'attr' => [
                    'readonly' => $options['create_by_phone'],
                ]
            ])

            ->add('approved', ChoiceType::class,[
                'label' => 'Approuvé',
                'choices' => [
                    'A modérer' => null,
                    'Valider' => true,
                    'Rejeter' => false
                ]
            ])
            ->add('update_at', DateType::class,[
                'label' => 'Date de la modération',
                'data' => new \DateTime('now')
            ])
            ->add('moderator', EntityType::class, [
                'label' => 'Modérateur',
                'class' => User::class,
                'choice_label' => 'lastname'
            ])
            ->add("create_by_phone", ChoiceType::class, [
            'label' => 'Témoignage récupéré par téléphone :',
            'choices' => [
                'Oui' => true,
                'Non' => false
            ],
                'placeholder' => "Veuillez choisir"
        ])
            ->add('content', TextareaType::class,[
                'label' => 'Commentaire',
                'attr' => [
                    'readonly' => $options['create_by_phone'],
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimonials::class,
            'create_by_phone' => false
        ]);
    }
}
