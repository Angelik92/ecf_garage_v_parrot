<?php

namespace App\Form;

use App\Data\FiltersData;
use App\Entity\Brands;
use App\Entity\Fuels;
use App\Entity\Gearboxes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltersAdsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quickSearch', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('brands', EntityType::class,[
                'class' => Brands::class,
                'mapped' => 'false',
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Marque',
                'required' => false
            ])
            ->add('min', IntegerType::class, [
                'label' => 'Prix',
                'required' => false,
                'attr' => [
                    'placeholder' => 'minimum'
                ]
            ]) ->add('max', IntegerType::class, [
                'label' => 'Prix',
                'required' => false,
                'attr' => [
                    'placeholder' => 'maximum'
                ]
            ])
            ->add('gearboxes', EntityType::class,[
                'class' => Gearboxes::class,
                'mapped' => 'false',
                'choice_label' => 'label',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Type de boite de vitesse',
                'required' => false
            ])
            ->add('fuels', EntityType::class,[
                'class' => Fuels::class,
                'mapped' => 'false',
                'choice_label' => 'label',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Type de carburant',
                'required' => false
            ])
            ->add('minKm', IntegerType::class,[
                'label' => 'Nombre km',
                'required' => false,
                'attr' => [
                    'placeholder' => 'minimum'
                ]
            ])
            ->add('maxKm', IntegerType::class,[
                'label' => 'Nombre km',
                'required' => false,
                'attr' => [
                    'placeholder' => 'maximum'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FiltersData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
