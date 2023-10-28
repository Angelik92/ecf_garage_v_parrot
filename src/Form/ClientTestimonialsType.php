<?php

namespace App\Form;


use App\Entity\Services;
use App\Entity\Testimonials;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientTestimonialsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('service', EntityType::class, [
                'label' => 'Service',
                'class' => Services::class,
                'choice_label' => 'title',
                'placeholder' => 'Choisir le service',
                'expanded' => true
            ])
            ->add('client', TextType::class, [
                'label' => 'Votre nom'
            ])
            ->add('date_of_service', DateType::class, [
                'label' => 'La date de service',
                'data' => new \DateTime('now')
            ])
            ->add('rating', HiddenType::class, [
                'label' => 'Note',
                'attr' => [
                    'id' => 'js-rating',
                    'value' => 0

    ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Votre commentaire'
            ])
            ->add('approved', HiddenType::class,[
                'data' => null
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimonials::class,
        ]);
    }
}
