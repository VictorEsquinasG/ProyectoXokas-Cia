<?php

namespace App\Entity;

use App\Repository\TramosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use stdClass;

#[ORM\Entity(repositoryClass: TramosRepository::class)]
class Tramos implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hora_inicio = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hora_fin = null;

    #[ORM\OneToMany(mappedBy: 'tramo', targetEntity: Evento::class)]
    private Collection $eventos;

    #[ORM\OneToMany(mappedBy: 'tramo', targetEntity: Reserva::class)]
    private Collection $reservas;

    public function __construct()
    {
        $this->eventos = new ArrayCollection();
        $this->reservas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHoraInicio(): ?\DateTimeInterface
    {
        return $this->hora_inicio;
    }

    public function setHoraInicio(\DateTimeInterface $hora_inicio): self
    {
        $this->hora_inicio = $hora_inicio;

        return $this;
    }

    public function getHoraFin(): ?\DateTimeInterface
    {
        return $this->hora_fin;
    }

    public function setHoraFin(\DateTimeInterface $hora_fin): self
    {
        $this->hora_fin = $hora_fin;

        return $this;
    }

    public function __toString(): string
    {
        return $this->hora_inicio->format('H:i') . " - " . $this->hora_fin->format('H:i');
    }

    public function jsonSerialize(): mixed
    {
        $json = new stdClass();
        
        $json->horaInicio = $this->getHoraFin();
        $json->horaFin = $this->getHoraFin();

        return $json;
    }

    /**
     * @return Collection<int, Evento>
     */
    public function getEventos(): Collection
    {
        return $this->eventos;
    }

    public function addEvento(Evento $evento): self
    {
        if (!$this->eventos->contains($evento)) {
            $this->eventos->add($evento);
            $evento->setTramo($this);
        }

        return $this;
    }

    public function removeEvento(Evento $evento): self
    {
        if ($this->eventos->removeElement($evento)) {
            // set the owning side to null (unless already changed)
            if ($evento->getTramo() === $this) {
                $evento->setTramo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reserva>
     */
    public function getReservas(): Collection
    {
        return $this->reservas;
    }

    public function addReserva(Reserva $reserva): self
    {
        if (!$this->reservas->contains($reserva)) {
            $this->reservas->add($reserva);
            $reserva->setTramo($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getTramo() === $this) {
                $reserva->setTramo(null);
            }
        }

        return $this;
    }

}
