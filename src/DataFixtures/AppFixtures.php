<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Announce;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{

        // Classe qui permet de générer des données factices
        protected Generator $faker;
    
        public function __construct(protected UserPasswordHasherInterface $hasher)
        {
            $this->faker = Factory::create();
        }

    public function load(ObjectManager $manager): void
    {

        // Création d'un utilisateur
        $user = new User();
        $user->setEmail('aze@aze.fr');
        $user->setName('aze');
        $user->setPassword($this->hasher->hashPassword($user, 'azeaze'));
        $user->setcreatedAt(new \DateTime());
        $manager->persist($user);

        // Création de plusieurs articles avec des données factices
        for ($i = 0; $i < 40; $i++) {
            $announce = new Announce();
            $announce->setTitle($this->faker->words(3, true));
            $announce->setDescription($this->faker->paragraphs(4, true));
            $announce->setPrice(50);
            $announce->setCity('Marseille');
            $announce->setZipcode(13000);
            $announce->setcreatedAt(new \DateTime());
            $announce->setupdatedAt(new \DateTime());
            $announce->setAuthor($user);
            $announce->setSlug($announce->getTitle());
            $manager->persist($announce);
        }

        $manager->flush();
    }
}
