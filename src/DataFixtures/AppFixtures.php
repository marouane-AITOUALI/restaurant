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
        // Créer des utilisateurs
        $userObjects = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFirstName("firstname$i")
                 ->setLastName("lastname$i")
                 ->setEmail("user$i@example.com")
                 ->setPassword($this->passwordEncoder->hashPassword($user, "password"))
                 ->setRoles(["ROLE_USER"])
                 ->setEstArchive(0);
            $manager->persist($user);
            $userObjects[] = $user;
        }

        // Créer des catégories
        $categories = ['Entrée', 'Plat Principal', 'Dessert', 'Boisson'];
        $categorieObjects = [];
        foreach ($categories as $catName) {
            $categorie = new Category();
            $categorie->setName($catName);
            $manager->persist($categorie);
            $categorieObjects[] = $categorie;
        }

        // Créer des plats avec ingrédients et commentaires
        $platObjects = [];
        for ($i = 1; $i <= 10; $i++) {
            $plat = new Plat();
            $plat->setName("Plat $i")
                 ->setPrice(mt_rand(1000, 5000) / 100)
                 ->setDescription("Description for Plat $i")
                 ->setIsDailySpecial(rand(0, 1))
                 ->setPays(['Maroc', 'Algerie', 'Tunisie'][array_rand(['Maroc', 'Algerie', 'Tunisie'])])
                 ->setCategory($categorieObjects[array_rand($categorieObjects)]);

            // Ajouter des ingrédients
            for ($j = 1; $j <= 3; $j++) {
                $ingredient = new Ingredient();
                $ingredient->setName("Ingredient $j for Plat $i")
                          ->setPlat($plat);
                $manager->persist($ingredient);
            }

            // Ajouter des commentaires
            for ($k = 0; $k < 2; $k++) {
                $commentaire = new Commentaire();
                $commentaire->setComment("Commentaire $k for Plat $i")
                            ->setUserId($userObjects[array_rand($userObjects)])
                            ->setPlat($plat)
                            ->setRating(rand(3, 5));
                $manager->persist($commentaire);
            }

            $manager->persist($plat);
            $platObjects[] = $plat;
        }

        // Créer des menus avec des plats
        for ($m = 0; $m < 3; $m++) {
            $menu = new Menu();
            $menu->setName("Menu $m")
                 ->setNombrePersonne(rand(2, 6));
            for ($p = 0; $p < 3; $p++) {
                $menu->addPlat($platObjects[array_rand($platObjects)]);
            }
            $manager->persist($menu);
        }

        // Créer des promotions
        for ($p = 0; $p < 2; $p++) {
            $promotion = new Promotion();
            $promotion->setPlat($platObjects[array_rand($platObjects)])
                      ->setDateDebut(new \DateTime('+'.rand(1, 30).' days'))
                      ->setDateFin(new \DateTime('+'.rand(31, 60).' days'))
                      ->setPourcentage(rand(10, 30));
            $manager->persist($promotion);
        }

        // Créer des tables
        $tableObjects = [];
        for ($t = 1; $t <= 5; $t++) {
            $table = new Table();
            $table->setNumero($t)
                  ->setCapacite(rand(2, 6))
                  ->setEstReserve(0);
            $manager->persist($table);
            $tableObjects[] = $table;
        }

        // Créer des réservations
        for ($r = 0; $r < 5; $r++) {
            $reservation = new Reservation();
            $reservation->setUserId($userObjects[array_rand($userObjects)])
                        ->setTableId($tableObjects[array_rand($tableObjects)])
                        ->setDate(new \DateTime('+'.rand(1, 15).' days'))
                        ->setStatus(rand(0, 2))
                        ->setIsEvent(rand(0, 1));
            $manager->persist($reservation);
        }

        // Créer des événements
        for ($e = 0; $e < 2; $e++) {
            $evenement = new Event();
            $evenement->setName("Evenement $e")
                      ->setDescription("Description for Evenement $e")
                      ->setDate(new \DateTime('+'.rand(1, 30).' days'))
                      ->setPrice(mt_rand(1000, 5000) / 100)
                      ->setPathImageEvent('./assets/images/events/koulMaghreb_event1.jpg');
            $manager->persist($evenement);
        }

        // Administrateur
        $admin = new User();
        $admin->setFirstName('adminFirstName')
              ->setLastName('adminLastName')
              ->setEmail('admin@example.com')
              ->setPassword($this->passwordEncoder->hashPassword($admin, 'password'))
              ->setRoles(['ROLE_ADMIN'])
              ->setEstArchive(0);
        $manager->persist($admin);

        // Utilisateur banni
        $ban = new User();
        $ban->setFirstName('banFirstName')
             ->setLastName('banLastName')
             ->setEmail('ban@example.com')
             ->setPassword($this->passwordEncoder->hashPassword($ban, 'password'))
             ->setRoles(['ROLE_BANNED'])
             ->setEstArchive(0);
        $manager->persist($ban);

        $manager->flush();
    }
}
