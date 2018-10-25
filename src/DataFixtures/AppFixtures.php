<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Cartes;
use App\Entity\Jetons;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$fullname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        foreach ($this->getJetonData() as [$img, $rang, $nom, $score]) {
            $jeton = new Jetons();
            $jeton->setImg($img);
            $jeton->setRang($rang);
            $jeton->setNom($nom);
            $jeton->setScore($score);

            $manager->persist($jeton);
            $this->setReference($nom, $jeton);
        }

        foreach ($this->getCarteData() as [$img, $rang, $nom]) {
            $carte = new Cartes();
            $carte->setImg($img);
            $carte->setRang($rang);
            $carte->setNom($nom);

            $manager->persist($carte);
            $this->setReference($nom, $carte);
        }
        $manager->flush();
    }

    private function getCarteData(): array
    {
        return [
            // $jetonData = [$img, $rang, $nom];
            ['baies_carte.png', 1, 'Baies'],
            ['baies_carte.png', 1, 'Baies'],
            ['baies_carte.png', 1, 'Baies'],
            ['baies_carte.png', 1, 'Baies'],
            ['baies_carte.png', 1, 'Baies'],
            ['baies_carte.png', 1, 'Baies'],
            ['baies_carte.png', 1, 'Baies'],
            ['baies_carte.png', 1, 'Baies'],
            ['baies_carte.png', 1, 'Baies'],
            ['baies_carte.png', 1, 'Baies'],
            ['poisson_carte.png', 2, 'Poisson'],
            ['poisson_carte.png', 2, 'Poisson'],
            ['poisson_carte.png', 2, 'Poisson'],
            ['poisson_carte.png', 2, 'Poisson'],
            ['poisson_carte.png', 2, 'Poisson'],
            ['poisson_carte.png', 2, 'Poisson'],
            ['poisson_carte.png', 2, 'Poisson'],
            ['poisson_carte.png', 2, 'Poisson'],
            ['outils_carte.png', 3, 'Outils'],
            ['outils_carte.png', 3, 'Outils'],
            ['outils_carte.png', 3, 'Outils'],
            ['outils_carte.png', 3, 'Outils'],
            ['outils_carte.png', 3, 'Outils'],
            ['outils_carte.png', 3, 'Outils'],
            ['outils_carte.png', 3, 'Outils'],
            ['outils_carte.png', 3, 'Outils'],
            ['armes_carte.png', 4, 'Armes'],
            ['armes_carte.png', 4, 'Armes'],
            ['armes_carte.png', 4, 'Armes'],
            ['armes_carte.png', 4, 'Armes'],
            ['armes_carte.png', 4, 'Armes'],
            ['armes_carte.png', 4, 'Armes'],
            ['viande_carte.png', 5, 'Viande'],
            ['viande_carte.png', 5, 'Viande'],
            ['viande_carte.png', 5, 'Viande'],
            ['viande_carte.png', 5, 'Viande'],
            ['viande_carte.png', 5, 'Viande'],
            ['viande_carte.png', 5, 'Viande'],
            ['feu_carte.png', 6, 'Feu'],
            ['feu_carte.png', 6, 'Feu'],
            ['feu_carte.png', 6, 'Feu'],
            ['feu_carte.png', 6, 'Feu'],
            ['feu_carte.png', 6, 'Feu'],
            ['feu_carte.png', 6, 'Feu'],
            ['mammouth_carte.png', 7, 'Mammouth'],
            ['mammouth_carte.png', 7, 'Mammouth'],
            ['mammouth_carte.png', 7, 'Mammouth'],
            ['mammouth_carte.png', 7, 'Mammouth'],
            ['mammouth_carte.png', 7, 'Mammouth'],
            ['mammouth_carte.png', 7, 'Mammouth'],
            ['mammouth_carte.png', 7, 'Mammouth'],
            ['mammouth_carte.png', 7, 'Mammouth'],
            ['mammouth_carte.png', 7, 'Mammouth'],
            ['mammouth_carte.png', 7, 'Mammouth'],
            ['mammouth_carte.png', 7, 'Mammouth'],
        ];
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['Jane Doe', 'jane_admin', 'kitten', 'jane_admin@symfony.com', ['ROLE_ADMIN']],
            ['Tom Doe', 'tom_admin', 'kitten', 'tom_admin@symfony.com', ['ROLE_ADMIN']],
            ['John Doe', 'john_user', 'kitten', 'john_user@symfony.com', ['ROLE_USER']],
        ];
    }

    private function getJetonData(): array
    {
        return [
            // $jetonData = [$img, $rang, $nom, $score];
            ['baies_jeton.png', 1, 'Baies', 1],
            ['baies_jeton.png', 1, 'Baies', 1],
            ['baies_jeton.png', 1, 'Baies', 1],
            ['baies_jeton.png', 1, 'Baies', 1],
            ['baies_jeton.png', 1, 'Baies', 1],
            ['baies_jeton.png', 1, 'Baies', 1],
            ['baies_jeton.png', 1, 'Baies', 2],
            ['baies_jeton.png', 1, 'Baies', 3],
            ['baies_jeton.png', 1, 'Baies', 4],
            ['poisson_jeton.png', 2, 'Poisson', 1],
            ['poisson_jeton.png', 2, 'Poisson', 1],
            ['poisson_jeton.png', 2, 'Poisson', 2],
            ['poisson_jeton.png', 2, 'Poisson', 2],
            ['poisson_jeton.png', 2, 'Poisson', 3],
            ['poisson_jeton.png', 2, 'Poisson', 3],
            ['poisson_jeton.png', 2, 'Poisson', 5],
            ['outils_jeton.png', 3, 'Outils', 1],
            ['outils_jeton.png', 3, 'Outils', 1],
            ['outils_jeton.png', 3, 'Outils', 2],
            ['outils_jeton.png', 3, 'Outils', 2],
            ['outils_jeton.png', 3, 'Outils', 3],
            ['outils_jeton.png', 3, 'Outils', 3],
            ['outils_jeton.png', 3, 'Outils', 5],
            ['armes_jeton.png', 4, 'Armes', 5],
            ['armes_jeton.png', 4, 'Armes', 5],
            ['armes_jeton.png', 4, 'Armes', 5],
            ['armes_jeton.png', 4, 'Armes', 5],
            ['armes_jeton.png', 4, 'Armes', 5],
            ['viande_jeton.png', 5, 'Viande', 5],
            ['viande_jeton.png', 5, 'Viande', 5],
            ['viande_jeton.png', 5, 'Viande', 5],
            ['viande_jeton.png', 5, 'Viande', 2],
            ['viande_jeton.png', 5, 'Viande', 2],
            ['feu_jeton.png', 6, 'Feu', 5],
            ['feu_jeton.png', 6, 'Feu', 5],
            ['feu_jeton.png', 6, 'Feu', 5],
            ['feu_jeton.png', 6, 'Feu', 7],
            ['feu_jeton.png', 6, 'Feu', 7],
            ['mammouth_jeton.png', 7, 'Mammouth', 0],
            ['territoire_jeton.png', 8, 'Territoire', 0],
            ['territoire_jeton.png', 8, 'Territoire', 0],
            ['territoire_jeton.png', 8, 'Territoire', 0],
            ['bonus3_jeton.png', 9, 'Bonus_3', 1],
            ['bonus3_jeton.png', 9, 'Bonus_3', 1],
            ['bonus3_jeton.png', 9, 'Bonus_3', 2],
            ['bonus3_jeton.png', 9, 'Bonus_3', 2],
            ['bonus3_jeton.png', 9, 'Bonus_3', 2],
            ['bonus3_jeton.png', 9, 'Bonus_3', 3],
            ['bonus3_jeton.png', 9, 'Bonus_3', 3],
            ['bonus4_jeton.png', 10, 'Bonus_4', 4],
            ['bonus4_jeton.png', 10, 'Bonus_4', 4],
            ['bonus4_jeton.png', 10, 'Bonus_4', 5],
            ['bonus4_jeton.png', 10, 'Bonus_4', 5],
            ['bonus4_jeton.png', 10, 'Bonus_4', 6],
            ['bonus4_jeton.png', 10, 'Bonus_4', 6],
            ['bonus5_jeton.png', 11, 'Bonus_5', 8],
            ['bonus5_jeton.png', 11, 'Bonus_5', 8],
            ['bonus5_jeton.png', 11, 'Bonus_5', 9],
            ['bonus5_jeton.png', 11, 'Bonus_5', 10],
            ['bonus5_jeton.png', 11, 'Bonus_5', 10],
        ];
    }

}