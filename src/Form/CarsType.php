<?php

namespace App\Form;

use App\Entity\Brands;
use App\Entity\Cars;
use App\Entity\Fuels;
use App\Entity\Gearboxes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', EntityType::class, [
                'label' => 'Marque',
                'class' => Brands::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir la marque'
            ])
            ->add('model', TextType::class, [
                'label' => 'Nom du modèle'
            ])
            ->add('Gearbox', EntityType::class, [
                'label' => 'Type de boite de vitesse',
                'class' => Gearboxes::class,
                'choice_label' => 'label',
                'placeholder' => 'Choisir le type de boite de vitesse'
            ])
            ->add('fuel', EntityType::class, [
                'label' => 'Type de carburant',
                'class' => Fuels::class,
                'choice_label' => 'label',
                'placeholder' => 'Choisir le type de carburant'
            ])
            ->add('power', IntegerType::class,[
                'label' => 'Puissance'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cars::class,
        ]);
    }
}
