<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Plat;
use App\Entity\Ingredient;
use App\Entity\Commentaire;
use App\Entity\Menu;
use App\Entity\Promotion;
use App\Entity\Table;
use App\Entity\Reservation;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        // Création des utilisateurs
        $userObjects = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFirstName("Prénom$i")
                ->setLastName("Nom$i")
                ->setEmail("user$i@example.com")
                ->setPassword("password")
                ->setRoles(["ROLE_USER"])
                ->setEstArchive(0);
            $manager->persist($user);
            $userObjects[] = $user;
        }

        // Création des catégories
        $categories = ['Entrée', 'Plat Principal', 'Dessert', 'Boisson'];
        $categorieObjects = [];
        foreach ($categories as $catName) {
            $categorie = new Category();
            $categorie->setName($catName);
            $manager->persist($categorie);
            $categorieObjects[] = $categorie;
        }

        // Liste des plats et ingrédients
        $platsData = [
            ["Couscous Royal", ["Semoule", "Agneau", "Merguez", "Pois chiches", "Légumes"]],
            ["Tajine de Poulet aux Olives", ["Poulet", "Olives vertes", "Citron confit", "Oignon", "Épices"]],
            ["Chorba Frik", ["Agneau", "Frik (blé concassé)", "Tomates", "Pois chiches", "Coriandre"]],
            ["Brik à l’Œuf", ["Feuille de brick", "Œuf", "Thon", "Persil", "Câpres"]],
            ["Rfissa", ["Trid (galette)", "Poulet", "Lentilles", "Fenugrec", "Épices"]],
            ["Loubia (Haricots Blancs)", ["Haricots blancs", "Tomates", "Ail", "Cumin", "Viande"]],
            ["Méchoui", ["Épaule d’agneau", "Ail", "Beurre", "Ras el hanout", "Sel"]],
            ["Makroud", ["Semoule", "Dattes", "Miel", "Beurre", "Fleur d’oranger"]],
            ["Zlabia", ["Farine", "Sucre", "Safran", "Eau de rose", "Levure"]],
            ["Thé à la Menthe", ["Thé vert", "Menthe fraîche", "Sucre", "Eau", "Fleur d’oranger"]],
        ];

        // Création des plats avec ingrédients
        $platObjects = [];
        foreach ($platsData as $platData) {
            [$nomPlat, $ingredients] = $platData;

            $plat = new Plat();
            $plat->setName($nomPlat)
                ->setPrice(mt_rand(1000, 5000) / 100)
                ->setDescription("Un délicieux $nomPlat préparé selon la tradition.")
                ->setIsDailySpecial(rand(0, 1))
                ->setPays(['Maroc', 'Algerie', 'Tunisie'][array_rand(['Maroc', 'Algerie', 'Tunisie'])])
                ->setCategory($categorieObjects[array_rand($categorieObjects)]);

            foreach ($ingredients as $ingredientName) {
                $ingredient = new Ingredient();
                $ingredient->setName($ingredientName)
                    ->setPlat($plat);
                $manager->persist($ingredient);
            }

            // Ajouter des commentaires
            for ($k = 0; $k < 2; $k++) {
                $commentaire = new Commentaire();
                $commentaire->setComment("Délicieux ! " . ($k + 1))
                    ->setUserId($userObjects[array_rand($userObjects)])
                    ->setPlat($plat)
                    ->setRating(rand(3, 5));
                $manager->persist($commentaire);
            }

            $manager->persist($plat);
            $platObjects[] = $plat;
        }

        // Création des menus
        for ($m = 0; $m < 3; $m++) {
            $menu = new Menu();
            $menu->setName("Menu $m")
                ->setNombrePersonne(rand(2, 6));
            for ($p = 0; $p < 3; $p++) {
                $menu->addPlat($platObjects[array_rand($platObjects)]);
            }
            $manager->persist($menu);
        }

        // Création des promotions
        for ($p = 0; $p < 2; $p++) {
            $promotion = new Promotion();
            $promotion->setPlat($platObjects[array_rand($platObjects)])
                ->setDateDebut(new \DateTime('+'.rand(1, 30).' days'))
                ->setDateFin(new \DateTime('+'.rand(31, 60).' days'))
                ->setPourcentage(rand(10, 30));
            $manager->persist($promotion);
        }

        // Création des tables
        $tableObjects = [];
        for ($t = 1; $t <= 5; $t++) {
            $table = new Table();
            $table->setNumero($t)
                ->setCapacite(rand(2, 6))
                ->setEstReserve(0);
            $manager->persist($table);
            $tableObjects[] = $table;
        }

        // Création des réservations
        for ($r = 0; $r < 5; $r++) {
            $reservation = new Reservation();
            $reservation->setUserId($userObjects[array_rand($userObjects)])
                ->setTableId($tableObjects[array_rand($tableObjects)])
                ->setDate(new \DateTime('+'.rand(1, 15).' days'))
                ->setStatus(rand(0, 2))
                ->setIsEvent(rand(0, 1));
            $manager->persist($reservation);
        }

        // Création des événements
        for ($e = 0; $e < 2; $e++) {
            $evenement = new Event();
            $evenement->setName("Soirée Spéciale $e")
                ->setDescription("Un événement exceptionnel pour découvrir nos saveurs.")
                ->setDate(new \DateTime('+'.rand(1, 30).' days'))
                ->setPrice(mt_rand(1000, 5000) / 100)
                ->setPathImageEvent("images/events/koulMaghreb_event" . ($e + 1) . ".jpg");
            $manager->persist($evenement);
        }

        // Création d'un administrateur
        $admin = new User();
        $admin->setFirstName('Admin')
            ->setLastName('Admin')
            ->setEmail('admin@example.com')
            ->setPassword('password')
            ->setRoles(['ROLE_ADMIN'])
            ->setEstArchive(0);
        $manager->persist($admin);

        $organisateurEvent = new User();
        $organisateurEvent->setFirstName('Organisateur')
            ->setLastName('Event')
            ->setEmail('organisateur@example.com')
            ->setPassword('password')
            ->setRoles(['ROLE_ORGANISATEUR_EVENT'])  // Attribuer le rôle d'organisateur
            ->setEstArchive(0);

        $manager->persist($organisateurEvent);

        $manager->flush();
    }
}