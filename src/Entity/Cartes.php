<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartesRepository")
 */
class Cartes
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
    private $carte_img;

    /**
     * @ORM\Column(type="integer")
     */
    private $carte_rang;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $carte_nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $carte_qte;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarteImg(): ?string
    {
        return $this->carte_img;
    }

    public function setCarteImg(string $carte_img): self
    {
        $this->carte_img = $carte_img;

        return $this;
    }

    public function getCarteRang(): ?int
    {
        return $this->carte_rang;
    }

    public function setCarteRang(int $carte_rang): self
    {
        $this->carte_rang = $carte_rang;

        return $this;
    }

    public function getCarteNom(): ?string
    {
        return $this->carte_nom;
    }

    public function setCarteNom(string $carte_nom): self
    {
        $this->carte_nom = $carte_nom;

        return $this;
    }

    public function getCarteQte(): ?int
    {
        return $this->carte_qte;
    }

    public function setCarteQte(int $carte_qte): self
    {
        $this->carte_qte = $carte_qte;

        return $this;
    }
}
