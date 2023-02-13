<?php

namespace App\Entity;

use App\Repository\EventoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventoRepository::class)]
class Evento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column]
    private ?int $num_max_asistentes = null;

    #[ORM\Column(length: 55, nullable: true)]
    private ?string $nombre = null;

    #[ORM\ManyToMany(targetEntity: Juego::class, mappedBy: 'Eventos')]
    private Collection $juegos;

    #[ORM\ManyToMany(targetEntity: Usuario::class, mappedBy: 'evento')]
    private Collection $usuarios;

    #[ORM\ManyToOne(inversedBy: 'eventos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tramos $tramo = null;

    public function __construct()
    {
        $this->juegos = new ArrayCollection();
        $this->usuarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNumMaxAsistentes(): ?int
    {
        return $this->num_max_asistentes;
    }

    public function setNumMaxAsistentes(int $num_max_asistentes): self
    {
        $this->num_max_asistentes = $num_max_asistentes;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, Juego>
     */
    public function getJuegos(): Collection
    {
        return $this->juegos;
    }

    public function addJuego(Juego $juego): self
    {
        if (!$this->juegos->contains($juego)) {
            $this->juegos->add($juego);
            $juego->addEvento($this);
        }

        return $this;
    }

    public function removeJuego(Juego $juego): self
    {
        if ($this->juegos->removeElement($juego)) {
            $juego->removeEvento($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Usuario>
     */
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(Usuario $usuario): self
    {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios->add($usuario);
            $usuario->addEvento($this);
        }

        return $this;
    }

    public function removeUsuario(Usuario $usuario): self
    {
        if ($this->usuarios->removeElement($usuario)) {
            $usuario->removeEvento($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNombre();
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
