<?php

namespace Evento\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \Evento\Repository\EntradaRepository::class)]
#[ORM\Table(name: "tbl_entrada")]
#[ORM\Index(name: "idx_entrada_evento", columns: ["evento_id"])]
#[ORM\Index(name: "idx_entrada_categoria", columns: ["categoria_id"])]
#[ORM\Index(name: "idx_entrada_estado", columns: ["estado"])]
class Entrada{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;


    #[ORM\Column(type: "integer", unique: true)]
    private $codigoReferencia;

    #[ORM\ManyToOne(targetEntity: Evento::class)]
    #[ORM\JoinColumn(name: "evento_id", referencedColumnName: "id")]
    private $evento;

    #[ORM\Column(type: "string", length: 50)]
    private $seccion;

    #[ORM\Column(type: "integer")]
    private $fila;

    #[ORM\Column(name: "asiento_numero", type: "integer")]
    private $asientoNumero;

    #[ORM\ManyToOne(targetEntity: Categoria::class)]
    #[ORM\JoinColumn(name: "categoria_id", referencedColumnName: "id")]
    private $categoria;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('activa', 'disponible', 'anulada')")]
    private $estado;

    public function getId(): int{
        return $this->id;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function getCodigoReferencia(): string{
        return $this->codigoReferencia;
    }

    public function setCodigoReferencia(string $codigoReferencia): void{
        $this->codigoReferencia = $codigoReferencia;
    }

    public function getEvento(): Evento{
        return $this->evento;
    }

    public function setEvento(Evento $evento): void{
        $this->evento = $evento;
    }

    public function getSeccion(): string{
        return $this->seccion;
    }

    public function setSeccion(string $seccion): void{
        $this->seccion = $seccion;
    }

    public function getFila(): int{
        return $this->fila;
    }

    public function setFila(int $fila): void{
        $this->fila = $fila;
    }

    public function getAsientoNumero(): int{
        return $this->asientoNumero;
    }

    public function setAsientoNumero(int $asientoNumero): void{
        $this->asientoNumero = $asientoNumero;
    }

    public function getCategoria(): Categoria{
        return $this->categoria;
    }

    public function setCategoria(Categoria $categoria): void{
        $this->categoria = $categoria;
    }

    public function getEstado(): string{
        return $this->estado;
    }

    public function setEstado(string $estado): void{
        $this->estado = $estado;
    }
}
