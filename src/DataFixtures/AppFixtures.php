<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Categorie;
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
            $user->setFirstName("firstname$i");
            $user->setLastName("lastname$i");
            $user->setEmail("user$i@example.com");
            $user->setPassword("password");
            $user->setRoles(["ROLE_USER"]);
            $user->setEstArchive(0);
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
        for ($i = 1; $i < 10; $i++) {
            $plat = new Plat();
            $plat->setName("Plat $i");
            $plat->setPrice(rand(10, 50));
            $plat->setDescription("Description for Plat $i");
            $plat->setIsDailySpecial(rand(0, 1));
            $plat->setPays(['Maroc', 'Algerie', 'Tunisie'][array_rand(['Maroc', 'Algerie', 'Tunisie'])]);
            $plat->setCategory($categorieObjects[array_rand($categorieObjects)]);

            // Ajouter des ingrédients
            for ($j = 1; $j < 4; $j++) {
                $ingredient = new Ingredient();
                $ingredient->setName("Ingredient $j for Plat $i");
                $ingredient->setPlat($plat);
                $manager->persist($ingredient);
            }

            // Ajouter des commentaires
            for ($k = 0; $k < 2; $k++) {
                $commentaire = new Commentaire();
                $commentaire->setComment("Commentaire $k for Plat $i");
                $commentaire->setUserId($userObjects[array_rand($userObjects)]);
                $commentaire->setPlat($plat);
                $manager->persist($commentaire);
            }

            $manager->persist($plat);
            $platObjects[] = $plat;
        }

        // Créer des menus avec des plats
        for ($m = 0; $m < 3; $m++) {
            $menu = new Menu();
            $menu->setName("Menu $m");
            for ($p = 0; $p < 3; $p++) {
                $menu->addPlat($platObjects[array_rand($platObjects)]);
            }
            $manager->persist($menu);
        }

        // Créer des promotions
        for ($p = 0; $p < 2; $p++) {
            $promotion = new Promotion();
            $promotion->setPlat($platObjects[array_rand($platObjects)]);
            $promotion->setDateDebut(new \DateTime('+'.rand(1, 30).' days'));
            $promotion->setDateFin(new \DateTime('+'.rand(31, 60).' days'));
            $promotion->setPourcentage(rand(10, 30));
            $manager->persist($promotion);
        }

        // Créer des tables
        for ($t = 1; $t <= 5; $t++) {
            $table = new Table();
            $table->setNumero($t);
            $table->setCapacite(rand(2, 6));
            $table->setEstReserve(0);
            $manager->persist($table);
        }

        // Créer des événements
        for ($e = 0; $e < 2; $e++) {
            $evenement = new Event();
            $evenement->setName("Evenement $e");
            $evenement->setDescription("Description for Evenement $e");
            $evenement->setDate(new \DateTime('+'.rand(1, 30).' days'));
            $manager->persist($evenement);
        }

        // Créer un administrateur
        $admin = new User();
        $admin->setFirstName('adminFirstName');
        $admin->setLastName('adminLastName');
        $admin->setEmail('admin@example.com');
        $admin->setPassword("password");
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setEstArchive(0);
        $manager->persist($admin);

        // Créer un administrateur
        $ban = new User();
        $ban->setFirstName('banFirstName');
        $ban->setLastName('banLastName');
        $ban->setEmail('ban@example.com');
        $ban->setPassword("password");
        $ban->setRoles(['ROLE_BANNED']);
        $ban->setEstArchive(0);
        $manager->persist($ban);

        $manager->flush();
    }
}
