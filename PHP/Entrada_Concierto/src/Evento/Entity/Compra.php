<?php

namespace Evento\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Evento\Entity\Entrada;

#[ORM\Entity(repositoryClass: \Evento\Repository\CompraRepository::class)]
#[ORM\Table(name: "tbl_compra")]
#[ORM\Index(name: "idx_compra_fecha", columns: ["fechaCompra"])]
#[ORM\Index(name: "idx_compra_email", columns: ["emailComprador"])]
class Compra{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 100)]
    private $nombreComprador;

    #[ORM\Column(type: "string", length: 100)]
    private $emailComprador;

    #[ORM\Column(type: "string", length: 16)]
    private $numeroTarjeta;

    #[ORM\Column(type: "datetime")]
    private $fechaCompra;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private $totalCompra;

    #[ORM\ManyToMany(targetEntity: Entrada::class)]
    #[ORM\JoinTable(name: "compra_entrada",
        joinColumns: [new ORM\JoinColumn(name: "compra_id", referencedColumnName: "id")],
        inverseJoinColumns: [new ORM\JoinColumn(name: "entrada_id", referencedColumnName: "id")]
    )]
    private $entradas;

    public function __construct(){
        $this->entradas = new ArrayCollection();
    }

    public function getId(): int{
        return $this->id;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function getNombreComprador(): string{
        return $this->nombreComprador;
    }

    public function setNombreComprador(string $nombreComprador): void{
        $this->nombreComprador = $nombreComprador;
    }

    public function getEmailComprador(): string{
        return $this->emailComprador;
    }

    public function setEmailComprador(string $emailComprador): void{
        $this->emailComprador = $emailComprador;
    }

    public function getNumeroTarjeta(): string{
        return $this->numeroTarjeta;
    }

    public function setNumeroTarjeta(string $numeroTarjeta): void{
        $this->numeroTarjeta = $numeroTarjeta;
    }

    public function getFechaCompra(): \DateTime{
        return $this->fechaCompra;
    }

    public function setFechaCompra(\DateTime $fechaCompra): void{
        $this->fechaCompra = $fechaCompra;
    }

    public function getTotalCompra(): float{
        return $this->totalCompra;
    }

    public function setTotalCompra(float $totalCompra): void{
        $this->totalCompra = $totalCompra;
    }

    public function getEntradas(): Collection{
        return $this->entradas;
    }

    public function addEntrada(Entrada $entrada): void{
        if (!$this->entradas->contains($entrada)) {
            $this->entradas[] = $entrada;
        }
    }

    public function removeEntrada(Entrada $entrada): void{
        $this->entradas->removeElement($entrada);
    }
}
