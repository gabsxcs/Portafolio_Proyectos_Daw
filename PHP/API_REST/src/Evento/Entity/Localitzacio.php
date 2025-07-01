<?php
namespace Evento\Entity;
use Doctrine\ORM\Mapping as ORM;

use Evento\Repository\LocalitzacioRepository;

#[ORM\Entity(repositoryClass: LocalitzacioRepository::class)]
#[ORM\Table(name: 'tbl_localitzacio')]
#[ORM\Index(columns: ['name'], name: 'idx_localitzacio_name')]
#[ORM\Index(columns: ['city'], name: 'idx_localitzacio_city')]
class Localitzacio {
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $address;

    #[ORM\Column(type: 'string', length: 100)]
    private $city;

    #[ORM\Column(type: 'integer')]
    private $capacity;
    
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }
}
