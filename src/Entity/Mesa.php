<?php

namespace App\Entity;

use App\Entity\Distribucion;
use App\Repository\MesaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: MesaRepository::class)]
class Mesa implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $largo = null;

    #[ORM\Column]
    private ?int $ancho = null;


    #[ORM\OneToMany(mappedBy: 'Mesa', targetEntity: Reserva::class)]
    private Collection $reservas;

    #[ORM\OneToMany(mappedBy: 'mesa_id', targetEntity: Distribucion::class, orphanRemoval: true)]
    private Collection $distribuciones;

    #[ORM\Column]
    private ?int $posicion_x = null;

    #[ORM\Column]
    private ?int $posicion_y = null;

    #[ORM\Column]
    private ?int $sillas = null;

    public function __construct()
    {
        $this->reservas = new ArrayCollection();
        $this->distribuciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLargo(): ?int
    {
        return $this->largo;
    }

    public function setLargo(int $largo): self
    {
        $this->largo = $largo;

        return $this;
    }

    public function getAncho(): ?int
    {
        return $this->ancho;
    }

    public function setAncho(int $ancho): self
    {
        $this->ancho = $ancho;

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
            $reserva->setMesa($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getMesa() === $this) {
                $reserva->setMesa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Distribucion>
     */
    public function getDistribuciones(): Collection
    {
        return $this->distribuciones;
    }

    public function addDistribucion(Distribucion $distribucion): self
    {
        if (!$this->distribuciones->contains($distribucion)) {
            $this->distribuciones->add($distribucion);
            $distribucion->setMesaId($this);
        }

        return $this;
    }

    public function removeDistribucion(Distribucion $distribucion): self
    {
        if ($this->distribuciones->removeElement($distribucion)) {
            // set the owning side to null (unless already changed)
            if ($distribucion->getMesaId() === $this) {
                $distribucion->setMesaId(null);
            }
        }

        return $this;
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

    public function getSillas(): ?int
    {
        return $this->sillas;
    }

    public function setSillas(int $sillas): self
    {
        $this->sillas = $sillas;

        return $this;
    }

    public function getDistribucionesNotLazy()
    {
        $return = [];
        $array = $this->getDistribuciones();
        # 
        foreach ($array as $elemento) {

            $elemento = json_encode($elemento);

            $return[] = $elemento;
        }
        // dd($return);

        return $return;
    }

    public function getReservasNotLazy()
    {
        $return = [];
        $array = $this->getReservas();
        # 
        foreach ($array as $elemento) {
            
            $elemento = json_encode($elemento);

            $return[] = $elemento;
        }
        // dd($return);
        return $return;
    }

    public function jsonSerialize(): mixed
    {
        $json = [
            "id" => $this->getId(),
            "ancho" => $this->getAncho(),
            "largo" => $this->getLargo(),
            "posicion_x" => $this->getPosicionX(),
            "posicion_y" => $this->getPosicionY(),
            "sillas" => $this->getSillas(),
            "distribuciones" => $this->getDistribucionesNotLazy(),
            "reservas" => $this->getReservasNotLazy(),
        ];
        return $json;
    }
}
