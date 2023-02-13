<?php

namespace App\Entity;

use App\Repository\DistribucionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use stdClass;

#[ORM\Entity(repositoryClass: DistribucionRepository::class)]
class Distribucion implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $posicion_x = null;

    #[ORM\Column]
    private ?int $posicion_y = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\ManyToOne(inversedBy: 'distribuciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mesa $mesa_id = null;


    #[ORM\Column(nullable: true)]
    private ?bool $reservada = null;

    #[ORM\Column(length: 35)]
    private ?string $alias = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosicionX(): ?int
    {
        return $this->posicion_x;
    }

    public function setPosicionX(int $posicion_x): self
    {
        $this->posicion_x = $posicion_x;

        return $this;
    }

    public function getPosicionY(): ?int
    {
        return $this->posicion_y;
    }

    public function setPosicionY(int $posicion_y): self
    {
        $this->posicion_y = $posicion_y;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getMesaId(): ?Mesa
    {
        return $this->mesa_id;
    }

    public function setMesaId(?Mesa $mesa_id): self
    {
        $this->mesa_id = $mesa_id;

        return $this;
    }

    public function isReservada(): ?bool
    {
        return $this->reservada;
    }

    public function setReservada(?bool $reservada): self
    {
        $this->reservada = $reservada;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getAlias();
    }

    public function jsonSerialize(): mixed
    {
        // $json = new stdClass();

        // $json->id = $this->getId();
        // $json->mesa = $this->getMesaId()->getId();
        // $json->fecha = $this->getFecha();
        // $json->pos_x = $this->getPosicionX();
        // $json->pos_y = $this->getPosicionY();
        // $json->alias = $this->getAlias();
        // $json->reservada = $this->isReservada();

        $json = [
            "id" => $this->getId(),
            "mesa" => $this->getMesaId()->getId(),
            "fecha" => $this->getFecha(),
            "pos_x" => $this->getPosicionX(),
            "pos_y" => $this->getPosicionY(),
            "alias" => $this->getAlias(),
            "reservada" => $this->isReservada(),
        ];

        return $json;
    }
}
