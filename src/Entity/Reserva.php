<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
class Reserva implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha_reserva = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha_cancelacion = null;

    #[ORM\Column(nullable: true)]
    private ?bool $asiste = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    private ?Usuario $Usuario = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Juego $Juego = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mesa $Mesa = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tramos $tramo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaReserva(): ?\DateTimeInterface
    {
        return $this->fecha_reserva;
    }

    public function setFechaReserva(\DateTimeInterface $fecha_reserva): self
    {
        $this->fecha_reserva = $fecha_reserva;

        return $this;
    }

    public function getFechaCancelacion(): ?\DateTimeInterface
    {
        return $this->fecha_cancelacion;
    }

    public function setFechaCancelacion(?\DateTimeInterface $fecha_cancelacion): self
    {
        $this->fecha_cancelacion = $fecha_cancelacion;

        return $this;
    }

    public function isAsiste(): ?bool
    {
        return $this->asiste;
    }

    public function setAsiste(?bool $asiste): self
    {
        $this->asiste = $asiste;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->Usuario;
    }

    public function setUsuario(?Usuario $Usuario): self
    {
        $this->Usuario = $Usuario;

        return $this;
    }

    public function getJuego(): ?Juego
    {
        return $this->Juego;
    }

    public function setJuego(?Juego $Juego): self
    {
        $this->Juego = $Juego;

        return $this;
    }

    public function getMesa(): ?Mesa
    {
        return $this->Mesa;
    }

    public function setMesa(?Mesa $Mesa): self
    {
        $this->Mesa = $Mesa;

        return $this;
    }

    public function __toString(): string
    {
        return $this->fecha_reserva->format("Y-m-d");
    }

    function jsonSerialize(): mixed
    {
        $json =
        [
            "id" => $this->getId(),
            "fecha_reserva" => $this->getFechaReserva(),
            "juego" => $this->getJuego(),
            "mesa" => $this->getMesa(),
            "usuario" => $this->getUsuario(),
            "asiste" => $this->isAsiste(),
            "fechaCancelacion" => $this->getFechaCancelacion(),
        ];
        return $json;
    }

    public function getTramo(): ?Tramos
    {
        return $this->tramo;
    }

    public function setTramo(?Tramos $tramo): self
    {
        $this->tramo = $tramo;

        return $this;
    }
}
