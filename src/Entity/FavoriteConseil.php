<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FavoriteConseil
 *
 * @ORM\Table(name="favorite_conseil", indexes={@ORM\Index(name="id_user", columns={"id_user"}), @ORM\Index(name="conseil_fav", columns={"id_conseil"})})
 * @ORM\Entity
 */
class FavoriteConseil
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Conseil
     *
     * @ORM\ManyToOne(targetEntity="Conseil")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_conseil", referencedColumnName="id_conseil")
     * })
     */
    private $idConseil;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdConseil(): ?Conseil
    {
        return $this->idConseil;
    }

    public function setIdConseil(?Conseil $idConseil): static
    {
        $this->idConseil = $idConseil;

        return $this;
    }

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }


}
