<?php

namespace Evento\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \Evento\Repository\EventoRepository::class)]
#[ORM\Table(name: "tbl_evento")]
#[ORM\Index(name: "idx_evento_nombre", columns: ["nombre"])]
#[ORM\Index(name: "idx_evento_fecha", columns: ["fecha"])]
#[ORM\Index(name: "idx_evento_artista", columns: ["artista"])]
#[ORM\Index(name: "idx_evento_lugar", columns: ["lugar"])]
class Evento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;
    
    #[ORM\Column(type: "string", length: 255)]
    private $nombre;
    
    #[ORM\Column(type: "date")]
    private $fecha;
    
    #[ORM\Column(type: "time")]
    private $hora;
    
    #[ORM\Column(type: "string", length: 255)]
    private $lugar;
    
    #[ORM\Column(type: "string", length: 255)]
    private $direccion;
    
    #[ORM\Column(type: "text", nullable: true)]
    private $descripcion;
    
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $imagen;
    
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $artista;
    
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
    
    public function getFecha(): \DateTime{
        return $this->fecha;
    }
    
    public function setFecha(\DateTime $fecha): void{
        $this->fecha = $fecha;
    }
    
    public function getHora(): \DateTime{
        return $this->hora;
    }
    
    public function setHora(\DateTime $hora): void{
        $this->hora = $hora;
    }
    
    public function getLugar(): string{
        return $this->lugar;
    }
    
    public function setLugar(string $lugar): void{
        $this->lugar = $lugar;
    }
    
    public function getDireccion(): string{
        return $this->direccion;
    }
    
    public function setDireccion(string $direccion): void{
        $this->direccion = $direccion;
    }
    
    public function getDescripcion(): ?string{
        return $this->descripcion;
    }
    
    public function setDescripcion(?string $descripcion): void{
        $this->descripcion = $descripcion;
    }
    
    public function getImagen(): ?string{
        return $this->imagen;
    }
    
    public function setImagen(?string $imagen): void{
        $this->imagen = $imagen;
    }
    
    public function getArtista(): ?string{
        return $this->artista;
    }
    
    public function setArtista(?string $artista): void{
        $this->artista = $artista;
    }
}
