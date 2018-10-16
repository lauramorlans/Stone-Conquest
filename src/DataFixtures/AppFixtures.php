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

        foreach ($this->getJetonData() as [$img, $rang, $nom, $qte, $score]) {
            $jeton = new Jetons();
            $jeton->setImg($img);
            $jeton->setRang($rang);
            $jeton->setNom($nom);
            $jeton->setQte($qte);
            $jeton->setScore($score);

            $manager->persist($jeton);
            $this->setReference($nom, $jeton);
        }

        foreach ($this->getCarteData() as [$img, $rang, $nom, $qte]) {
            $carte = new Cartes();
            $carte->setImg($img);
            $carte->setRang($rang);
            $carte->setNom($nom);
            $carte->setQte($qte);

            $manager->persist($carte);
            $this->setReference($nom, $carte);
        }
        $manager->flush();
    }

    private function getCarteData(): array
    {
        return [
            // $jetonData = [$img, $rang, $nom, $qte];
            ['baies_carte.png', 1, 'Baies', 10],
            ['poisson_carte.png', 2, 'Poisson', 8],
            ['outils_carte.png', 3, 'Outils', 8],
            ['armes_carte.png', 4, 'Armes', 6],
            ['viande_carte.png', 5, 'Viande', 6],
            ['feu_carte.png', 6, 'Feu', 6],
            ['mammouth_carte.png', 7, 'Mammouth', 11],
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
            // $jetonData = [$img, $rang, $nom, $qte, $score];
            ['baies_jeton.png', 1, 'Baies', 6, 1],
            ['baies_jeton.png', 1, 'Baies', 1, 2],
            ['baies_jeton.png', 1, 'Baies', 1, 3],
            ['baies_jeton.png', 1, 'Baies', 1, 4],
            ['poisson_jeton.png', 2, 'Poisson', 2, 1],
            ['poisson_jeton.png', 2, 'Poisson', 2, 2],
            ['poisson_jeton.png', 2, 'Poisson', 2, 3],
            ['poisson_jeton.png', 2, 'Poisson', 1, 5],
            ['outils_jeton.png', 3, 'Outils', 2, 1],
            ['outils_jeton.png', 3, 'Outils', 2, 2],
            ['outils_jeton.png', 3, 'Outils', 2, 3],
            ['outils_jeton.png', 3, 'Outils', 1, 5],
            ['armes_jeton.png', 4, 'Armes', 3, 5],
            ['armes_jeton.png', 4, 'Armes', 2, 6],
            ['viande_jeton.png', 5, 'Viande', 3, 5],
            ['viande_jeton.png', 5, 'Viande', 2, 7],
            ['feu_jeton.png', 6, 'Feu', 6, 5],
            ['mammouth_jeton.png', 7, 'Mammouth', 1, 0],
            ['territoire_jeton.png', 8, 'Territoire', 3, 0],
            ['bonus3_jeton.png', 9, 'Bonus 3', 2, 1],
            ['bonus3_jeton.png', 9, 'Bonus 3', 3, 2],
            ['bonus3_jeton.png', 9, 'Bonus 3', 2, 3],
            ['bonus4_jeton.png', 10, 'Bonus 4', 2, 4],
            ['bonus4_jeton.png', 10, 'Bonus 4', 2, 5],
            ['bonus4_jeton.png', 10, 'Bonus 4', 2, 6],
            ['bonus5_jeton.png', 11, 'Bonus 5', 2, 8],
            ['bonus5_jeton.png', 11, 'Bonus 5', 1, 9],
            ['bonus5_jeton.png', 11, 'Bonus 5', 2, 10],
        ];
    }

}