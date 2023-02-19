<?php

namespace App\Entity;

use App\Repository\JuegoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: JuegoRepository::class)]
class Juego implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $min_jugadores = null;

    #[ORM\Column]
    private ?int $max_jugadores = null;


    #[ORM\ManyToMany(targetEntity: Evento::class, inversedBy: 'juegos')]
    private Collection $Eventos;

    #[ORM\OneToMany(mappedBy: 'Juego', targetEntity: Reserva::class)]
    private Collection $reservas;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private $imagen = null;

    #[ORM\Column]
    private ?int $anchoTablero = null;

    #[ORM\Column]
    private ?int $largoTablero = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    public function __construct()
    {
        $this->Eventos = new ArrayCollection();
        $this->reservas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getMinJugadores(): ?int
    {
        return $this->min_jugadores;
    }

    public function setMinJugadores(int $min_jugadores): self
    {
        $this->min_jugadores = $min_jugadores;

        return $this;
    }

    public function getMaxJugadores(): ?int
    {
        return $this->max_jugadores;
    }

    public function setMaxJugadores(int $max_jugadores): self
    {
        $this->max_jugadores = $max_jugadores;

        return $this;
    }


    /**
     * @return Collection<int, Evento>
     */
    public function getEventos(): Collection
    {
        return $this->Eventos;
    }

    public function addEvento(Evento $evento): self
    {
        if (!$this->Eventos->contains($evento)) {
            $this->Eventos->add($evento);
        }

        return $this;
    }

    public function removeEvento(Evento $evento): self
    {
        $this->Eventos->removeElement($evento);

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
            $reserva->setJuego($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getJuego() === $this) {
                $reserva->setJuego(null);
            }
        }

        return $this;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getAnchoTablero(): ?int
    {
        return $this->anchoTablero;
    }

    public function setAnchoTablero(int $anchoTablero): self
    {
        $this->anchoTablero = $anchoTablero;

        return $this;
    }

    public function getLargoTablero(): ?int
    {
        return $this->largoTablero;
    }

    public function setLargoTablero(int $largoTablero): self
    {
        $this->largoTablero = $largoTablero;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nombre;
    }

    public function jsonSerialize(): mixed
    {

        $reservas = [];
        $eventos = [];
        $r = $this->getReservas();
        $e = $this->getEventos();
        foreach ($r as $reserva) {
            $reservas[] = $reserva;
        }
        foreach ($e as $evento) {
            $eventos[] = $evento;
        }

        $json = [
            "id" => $this->getId(),
            "nombre" => $this->getNombre(),
            "descripcion" => $this->getDescripcion(),
            "tablero" => [
                "ancho" => $this->getAnchoTablero(),
                "largo" => $this->getLargoTablero()
            ],
            "jugadores" => [
                "min" => $this->getMinJugadores(),
                "max" => $this->getMaxJugadores()
            ],
            "imagen" => $this->getImagen(),
            "reservas" => $this->reservas->map(function (Reserva $reserva) {
                return [
                    'id' => $reserva->getId(),
                    'fechaReserva' => $reserva->getFechaReserva(),
                    'mesa' => $reserva->getMesa(),
                    'tramo' => $reserva->getTramo(),
                    'usuario' => $reserva->getUsuario(),
                ];
            })->toArray(),
            "eventos" => $this->Eventos->map(function (Evento $evento)
            {
                return [
                  "id" => $evento->getId(),
                  "nombre" => $evento->getNombre(),  
                  "fecha" => $evento->getFecha(),
                  "tramo" => $evento->getTramo(),  
                  "numMaxAsistentes" => $evento->getNumMaxAsistentes(),  
                ];
            })->toArray(),
            "string" => $this->__toString()
        ];
        return $json;
    }
}
