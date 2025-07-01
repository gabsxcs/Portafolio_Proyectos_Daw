<?php
namespace Evento\Entity;

use Doctrine\ORM\Mapping as ORM;
use Evento\Repository\SeientRepository;

#[ORM\Entity(repositoryClass: SeientRepository::class)]
#[ORM\Table(name: 'tbl_seient')]
#[ORM\Index(columns: ['venue_id'], name: 'idx_seient_venue')]
#[ORM\Index(columns: ['fila', 'number'], name: 'idx_seient_fila_number')]
class Seient {
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    private $id;

    #[ORM\Column(type: 'string', length: 10)]
    private $fila;

    #[ORM\Column(type: 'integer')]
    private $number;

    #[ORM\Column(type: 'string', length: 20)]
    private  $type;

    #[ORM\ManyToOne(targetEntity: Localitzacio::class)]
    #[ORM\JoinColumn(name: 'venue_id', referencedColumnName: 'id')]
    private  $venue;


    public function getId(): int
    {
        return $this->id;
    }

    public function getFila(): string
    {
        return $this->fila;
    }

    public function setFila(string $fila): void
    {
        $this->fila = $fila;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getVenue(): Localitzacio
    {
        return $this->venue;
    }

    public function setVenue(Localitzacio $venue): void
    {
        $this->venue = $venue;
    }
}
