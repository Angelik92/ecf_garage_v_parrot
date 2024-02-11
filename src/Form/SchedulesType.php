<?php

namespace App\Form;

use App\Entity\Schedules;
use Symfony\Component\Form\AbstractType;
use App\Entity\Enumerate\Days;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchedulesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('day', EnumType::class, [
                'class' => Days::class,
                'label' => 'Jour',
            ])
            ->add('morning_schedule', TextType::class, [
                'label' => 'Matin ou journée',
            ])
            ->add('afternoon_schedule', TextType::class, [
                'label' => 'Après midi',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Schedules::class,
        ]);
    }
}
