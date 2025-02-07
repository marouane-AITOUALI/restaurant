<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Menu;
use App\Entity\Plat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use App\Repository\MenuRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('is_daily_special', ChoiceType::class, [
                'label' => 'Plat du jour',
                'choices' => [
                    'Faux' => 0,
                    'Vrai' => 1,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('pays')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('menus', EntityType::class, [
                'class' => Menu::class,
                'choice_label' => 'name', // Afficher le NOM du menu (au lieu de l'id)
                'multiple' => true, // Choix multiple
                'expanded' => true, // Afficher des checkboxes
                'query_builder' => function (MenuRepository $menuRepository) {
                    return $menuRepository->createQueryBuilder('m')
                        ->orderBy('m.name', 'ASC'); // Optionnel : trier les menus
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}