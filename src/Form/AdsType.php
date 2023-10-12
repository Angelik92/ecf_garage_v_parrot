<?php

namespace App\Form;

use App\Entity\Ads;
use App\Entity\Cars;
use App\Entity\User;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class AdsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('create_at', DateType::class, [
                'label' => 'Date de création',
                'data' => new \DateTime('now'),

            ])
            ->add('author', EntityType::class, [
                'label' => 'Auteur',
                'class' => User::class,
                'choice_label' => 'lastname',

            ])
            ->add('registration_nb', TextType::class, [
                'label' => 'Immatriculation'
            ])
            ->add('built', IntegerType::class, [
                'label' => 'Année de construction'
            ])
            ->add('kilometers', IntegerType::class, [
                'label' => 'Nombre de kilomètre'
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix'
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('car', EntityType::class,[
                'label' => 'Modèle',
                'class' => Cars::class,
                'choice_label' => 'model',
                'placeholder' => 'Choisir un modèle'
            ])
            ->add('pictures', FileType::class, [
                'label' => "Photos",
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
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
