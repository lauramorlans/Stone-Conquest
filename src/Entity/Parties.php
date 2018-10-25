<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartiesRepository")
 */
class Parties
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $partie_date;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $partie_statue;

    /**
     * @ORM\Column(type="json_array")
     */
    private $partie_terrain;

    /**
     * @ORM\Column(type="json_array")
     */
    private $partie_pioche;

    /**
     * @ORM\Column(type="integer")
     */
    private $jeton_chameaux;

    /**
     * @ORM\Column(type="boolean")
     */
    private $partie_defausse;

    /**
     * @ORM\Column(type="json_array")
     */
    private $main_j1;

    /**
     * @ORM\Column(type="json_array")
     */
    private $main_j2;

    /**
     * @ORM\Column(type="json_array")
     */
    private $chameaux_j1;

    /**
     * @ORM\Column(type="json_array")
     */
    private $chameaux_j2;

    /**
     * @ORM\Column(type="json_array")
     */
    private $jetons_j1;

    /**
     * @ORM\Column(type="json_array")
     */
    private $jetons_j2;

    /**
     * @ORM\Column(type="integer")
     */
    private $jetons_victoirej1;

    /**
     * @ORM\Column(type="integer")
     */
    private $jetons_victoirej2;

    /**
     * @ORM\Column(type="json_array")
     */
    private $jetons_terrain;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_manche;

    /**
     * @ORM\Column(type="integer")
     */
    private $point_j1;

    /**
     * @ORM\Column(type="integer")
     */
    private $point_j2;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $joueur1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $joueur2;

    /**
     * @ORM\Column(type="integer")
     */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartieDate(): ?\DateTimeInterface
    {
        return $this->partie_date;
    }

    public function setPartieDate(\DateTimeInterface $partie_date): self
    {
        $this->partie_date = $partie_date;

        return $this;
    }

    public function getPartieStatue(): ?string
    {
        return $this->partie_statue;
    }

    public function setPartieStatue(string $partie_statue): self
    {
        $this->partie_statue = $partie_statue;

        return $this;
    }

    public function getPartieTerrain()
    {
        return $this->partie_terrain;
    }

    public function setPartieTerrain($partie_terrain): self
    {
        $this->partie_terrain = $partie_terrain;

        return $this;
    }

    public function getPartiePioche()
    {
        return $this->partie_pioche;
    }

    public function setPartiePioche($partie_pioche): self
    {
        $this->partie_pioche = $partie_pioche;

        return $this;
    }

    public function getJetonChameaux()
    {
        return $this->jeton_chameaux;
    }

    public function setJetonChameaux($jeton_chameaux): self
    {
        $this->jeton_chameaux = $jeton_chameaux;

        return $this;
    }

    public function getPartieDefausse(): ?bool
    {
        return $this->partie_defausse;
    }

    public function setPartieDefausse(bool $partie_defausse): self
    {
        $this->partie_defausse = $partie_defausse;

        return $this;
    }

    public function getMainJ1()
    {
        return $this->main_j1;
    }

    public function setMainJ1($main_j1): self
    {
        $this->main_j1 = $main_j1;

        return $this;
    }

    public function getMainJ2()
    {
        return $this->main_j2;
    }

    public function setMainJ2($main_j2): self
    {
        $this->main_j2 = $main_j2;

        return $this;
    }

    public function getChameauxJ1()
    {
        return $this->chameaux_j1;
    }

    public function setChameauxJ1($chameaux_j1): self
    {
        $this->chameaux_j1 = $chameaux_j1;

        return $this;
    }

    public function getChameauxJ2()
    {
        return $this->chameaux_j2;
    }

    public function setChameauxJ2($chameaux_j2): self
    {
        $this->chameaux_j2 = $chameaux_j2;

        return $this;
    }

    public function getJetonsJ1()
    {
        return $this->jetons_j1;
    }

    public function setJetonsJ1($jetons_j1): self
    {
        $this->jetons_j1 = $jetons_j1;

        return $this;
    }

    public function getJetonsJ2()
    {
        return $this->jetons_j2;
    }

    public function setJetonsJ2($jetons_j2): self
    {
        $this->jetons_j2 = $jetons_j2;

        return $this;
    }

    public function getJetonsVictoireJ1(): ?int
    {
        return $this->jetons_victoirej1;
    }

    public function setJetonsVictoireJ1(int $jetons_victoirej1): self
    {
        $this->jetons_victoirej1 = $jetons_victoirej1;

        return $this;
    }

    public function getJetonsVictoireJ2(): ?int
    {
        return $this->jetons_victoirej2;
    }

    public function setJetonsVictoireJ2(int $jetons_victoirej2): self
    {
        $this->jetons_victoirej2 = $jetons_victoirej2;

        return $this;
    }

    public function getJetonsTerrain()
    {
        return $this->jetons_terrain;
    }

    public function setJetonsTerrain($jetons_terrain): self
    {
        $this->jetons_terrain = $jetons_terrain;

        return $this;
    }

    public function getNbManche(): ?int
    {
        return $this->nb_manche;
    }

    public function setNbManche(int $nb_manche): self
    {
        $this->nb_manche = $nb_manche;

        return $this;
    }

    public function getPointJ1(): ?int
    {
        return $this->point_j1;
    }

    public function setPointJ1(int $point_j1): self
    {
        $this->point_j1 = $point_j1;

        return $this;
    }

    public function getPointJ2(): ?int
    {
        return $this->point_j2;
    }

    public function setPointJ2(int $point_j2): self
    {
        $this->point_j2 = $point_j2;

        return $this;
    }

    public function getJoueur1(): ?User
    {
        return $this->joueur1;
    }

    public function setJoueur1(?User $joueur1): self
    {
        $this->joueur1 = $joueur1;

        return $this;
    }

    public function getJoueur2(): ?User
    {
        return $this->joueur2;
    }

    public function setJoueur2(?User $joueur2): self
    {
        $this->joueur2 = $joueur2;

        return $this;
    }

}
