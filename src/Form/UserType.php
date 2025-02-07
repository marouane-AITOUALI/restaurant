<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    'Banned' => 'ROLE_BANNED',
                ],
                'multiple' => true,
                'expanded' => true,  
            ])
            ->add('firstName')
            ->add('lastName')
            ->add('est_archive', ChoiceType::class, [
                'choices' => [
                    '❌ Faux' => 0,
                    '✅ Vrai' => 1,
                ],
            ])
            ->add('isVerified', ChoiceType::class, [
                'choices' => [
                    '❌' => 0,
                    '✅' => 1,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
