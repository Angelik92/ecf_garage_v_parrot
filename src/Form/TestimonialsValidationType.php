<?php

namespace App\Form;

use App\Entity\Testimonials;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimonialsValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('moderator', EntityType::class, [
                'label' => 'Modérateur',
                'class' => User::class,
                'choice_label' => 'lastname'
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

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimonials::class,
        ]);
    }
}
