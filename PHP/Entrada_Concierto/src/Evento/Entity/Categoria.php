<?php

namespace Evento\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \Evento\Repository\CategoriaRepository::class)]
#[ORM\Table(name: "tbl_categoria")]
#[ORM\Index(name: "idx_categoria_nombre", columns: ["nombre"])]
#[ORM\Index(name: "idx_categoria_evento", columns: ["evento_id"])]
class Categoria{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 50)]
    private $nombre;

    #[ORM\Column(type: "text", nullable: true)]
    private $descripcion;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private $valor;

    #[ORM\ManyToOne(targetEntity: Evento::class)]
    #[ORM\JoinColumn(name: "evento_id", referencedColumnName: "id", nullable: false)]
    private $evento;

    public function getId(): int{
        return $this->id;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function getNombre(): string{
        return $this->nombre;
    }

    public function setNombre(string $nombre): void{
        $this->nombre = $nombre;
    }

    public function getDescripcion(): ?string{
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): void{
        $this->descripcion = $descripcion;
    }

    public function getValor(): float{
        return $this->valor;
    }

    public function setValor(float $valor): void{
        $this->valor = $valor;
    }

    public function getEvento(): Evento{
        return $this->evento;
    }

    public function setEvento(Evento $evento): void{
        $this->evento = $evento;
    }
}
