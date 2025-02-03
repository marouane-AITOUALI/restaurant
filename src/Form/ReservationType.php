<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Reservation;
use App\Entity\Table;
use App\Entity\User;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'En attente' => 0,
                    'Confirmée' => 1,
                    'Refusée' => 2,
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('isEvent', ChoiceType::class, [
                'choices' => [
                    'Non' => 0,
                    'Oui' => 1,
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('phone', TextType::class, [
                'required' => false,
            ])
            ->add('number_of_people', IntegerType::class, [
                'required' => false,
            ])
            ->add('notes', TextType::class, [
                'required' => false,
            ])
            ->add('table_id', EntityType::class, [
                'class' => Table::class,
                'choice_label' => 'id',
            ])
            ->add('user_id', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
