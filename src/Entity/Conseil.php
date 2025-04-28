<?php

namespace App\Entity;

use App\Entity\Typeconseil;
use App\Entity\Produit;
use App\Repository\ConseilRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConseilRepository")
 */
class Conseil
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_conseil", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idConseil;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_conseil", type="string", length=255, nullable=false)
     */
    private $nomConseil;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=255, nullable=false)
     */
    private $video;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime", nullable=false)
     */
    private $datecreation;

    /**
     * @var \Typeconseil
     *
     * @ORM\ManyToOne(targetEntity="Typeconseil")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_typeC", referencedColumnName="idTypeC")
     * })
     */
    private $idTypec;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_produit", referencedColumnName="id_produit")
     * })
     */
    private $idProduit;

    public function getIdConseil(): ?int
    {
        return $this->idConseil;
    }

    public function getNomConseil(): ?string
    {
        return $this->nomConseil;
    }

    public function setNomConseil(string $nomConseil): static
    {
        $this->nomConseil = $nomConseil;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): static
    {
        $this->video = $video;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): static
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getIdTypec(): ?Typeconseil
    {
        return $this->idTypec;
    }

    public function setIdTypec(?Typeconseil $idTypec): static
    {
        $this->idTypec = $idTypec;

        return $this;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Produit $idProduit): static
    {
        $this->idProduit = $idProduit;

        return $this;
    }
}
