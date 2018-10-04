<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateursRepository")
 */
class Utilisateurs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $utilisateur_nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $utilisateur_password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $utilisateur_pseudo;

    /**
     * @ORM\Column(type="text")
     */
    private $utilisateur_mail;

    /**
     * @ORM\Column(type="integer")
     */
    private $utilisateur_score;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Parties", mappedBy="joueur1")
     */
    private $joueur1;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Parties", mappedBy="joueur2")
     */
    private $joueur2;

    /**
     * @var array
     *
     * @ORM\Column(type="string")
     */
    private $rang = [];

    public function __construct()
    {
        $this->joueur1 = new ArrayCollection();
        $this->joueur2 = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateurNom(): ?string
    {
        return $this->utilisateur_nom;
    }

    public function setUtilisateurNom(string $utilisateur_nom): self
    {
        $this->utilisateur_nom = $utilisateur_nom;

        return $this;
    }

    public function getUtilisateurPassword(): ?string
    {
        return $this->utilisateur_password;
    }

    public function setUtilisateurPassword(string $utilisateur_password): self
    {
        $this->utilisateur_password = $utilisateur_password;

        return $this;
    }

    public function getUtilisateurPseudo(): ?string
    {
        return $this->utilisateur_pseudo;
    }

    public function setUtilisateurPseudo(string $utilisateur_pseudo): self
    {
        $this->utilisateur_pseudo = $utilisateur_pseudo;

        return $this;
    }

    public function getUtilisateurMail(): ?string
    {
        return $this->utilisateur_mail;
    }

    public function setUtilisateurMail(string $utilisateur_mail): self
    {
        $this->utilisateur_mail = $utilisateur_mail;

        return $this;
    }

    public function getUtilisateurScore(): ?int
    {
        return $this->utilisateur_score;
    }

    public function setUtilisateurScore(int $utilisateur_score): self
    {
        $this->utilisateur_score = $utilisateur_score;

        return $this;
    }

    /**
     * Retourne les rôles de l'user
     */
    public function getRang(): array
    {
        $rang = $this->rang;

        // Afin d'être sûr qu'un user a toujours au moins 1 rôle
        if (empty($rang)) {
            $rang[] = 'ROLE_USER';
        }

        return array_unique($rang);
    }

    public function setRang(array $rang): void
    {
        $this->rang = $rang;
    }

    /**
     * @return Collection|Parties[]
     */
    public function getJoueur1(): Collection
    {
        return $this->joueur1;
    }

    public function addJoueur1(Parties $joueur1): self
    {
        if (!$this->joueur1->contains($joueur1)) {
            $this->joueur1[] = $joueur1;
            $joueur1->setJoueur1($this);
        }

        return $this;
    }

    public function removeJoueur1(Parties $joueur1): self
    {
        if ($this->joueur1->contains($joueur1)) {
            $this->joueur1->removeElement($joueur1);
            // set the owning side to null (unless already changed)
            if ($joueur1->getJoueur1() === $this) {
                $joueur1->setJoueur1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Parties[]
     */
    public function getJoueur2(): Collection
    {
        return $this->joueur2;
    }

    public function addJoueur2(Parties $joueur2): self
    {
        if (!$this->joueur2->contains($joueur2)) {
            $this->joueur2[] = $joueur2;
            $joueur2->setJoueur2($this);
        }

        return $this;
    }

    public function removeJoueur2(Parties $joueur2): self
    {
        if ($this->joueur2->contains($joueur2)) {
            $this->joueur2->removeElement($joueur2);
            // set the owning side to null (unless already changed)
            if ($joueur2->getJoueur2() === $this) {
                $joueur2->setJoueur2(null);
            }
        }

        return $this;
    }

    public function getUtilisateurRang(): ?bool
    {
        return $this->utilisateur_rang;
    }

    public function setUtilisateurRang(bool $utilisateur_rang): self
    {
        $this->utilisateur_rang = $utilisateur_rang;

        return $this;
    }

    /**
     * Retour le salt qui a servi à coder le mot de passe
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one

        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // Nous n'avons pas besoin de cette methode car nous n'utilions pas de plainPassword
        // Mais elle est obligatoire car comprise dans l'interface UserInterface
        // $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([$this->id, $this->utilisateur_pseudo, $this->utilisateur_password]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->utilisateur_pseudo, $this->utilisateur_password] = unserialize($serialized, ['allowed_classes' => false]);
    }
}
