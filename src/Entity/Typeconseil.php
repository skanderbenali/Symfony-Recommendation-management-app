<?php

namespace App\Entity;

use App\Repository\TypeConseilRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeConseilRepository")
 */
class Typeconseil
{
    /**
     * @var int
     *
     * @ORM\Column(name="idTypeC", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtypec;

    /**
     * @var string
     *
     * @ORM\Column(name="nomTypeC", type="string", length=255, nullable=false)
     */
    private $nomtypec;

    public function getIdtypec(): ?int
    {
        return $this->idtypec;
    }

    public function getNomtypec(): ?string
    {
        return $this->nomtypec;
    }

    public function setNomtypec(string $nomtypec): static
    {
        $this->nomtypec = $nomtypec;

        return $this;
    }


}
