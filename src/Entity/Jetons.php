<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JetonsRepository")
 */
class Jetons
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
    private $jeton_img;

    /**
     * @ORM\Column(type="integer")
     */
    private $jeton_rang;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $jeton_nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $jeton_qte;

    /**
     * @ORM\Column(type="integer")
     */
    private $jeton_score;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJetonImg(): ?string
    {
        return $this->jeton_img;
    }

    public function setJetonImg(string $jeton_img): self
    {
        $this->jeton_img = $jeton_img;

        return $this;
    }

    public function getJetonRang(): ?int
    {
        return $this->jeton_rang;
    }

    public function setJetonRang(int $jeton_rang): self
    {
        $this->jeton_rang = $jeton_rang;

        return $this;
    }

    public function getJetonNom(): ?string
    {
        return $this->jeton_nom;
    }

    public function setJetonNom(string $jeton_nom): self
    {
        $this->jeton_nom = $jeton_nom;

        return $this;
    }

    public function getJetonQte(): ?int
    {
        return $this->jeton_qte;
    }

    public function setJetonQte(int $jeton_qte): self
    {
        $this->jeton_qte = $jeton_qte;

        return $this;
    }

    public function getJetonScore(): ?int
    {
        return $this->jeton_score;
    }

    public function setJetonScore(int $jeton_score): self
    {
        $this->jeton_score = $jeton_score;

        return $this;
    }
}
