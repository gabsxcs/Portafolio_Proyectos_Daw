<?php
namespace Evento\Entity;
use Doctrine\ORM\Mapping as ORM;
use Evento\Repository\EsdevenimentRepository;
#[ORM\Entity(repositoryClass: EsdevenimentRepository::class)]
#[ORM\Table(name: 'tbl_esdeveniment')]
#[ORM\Index(columns: ['title'], name: 'idx_esdeveniment_title')]

class Esdeveniment {
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private  $title;

    #[ORM\Column(type: 'text')]
    private  $description;

    #[ORM\Column(type: 'datetime')]
    private  $startTime;

    #[ORM\Column(type: 'datetime')]
    private  $endTime;

    #[ORM\Column(type: 'string', length: 50)]
    private string $type;

    #[ORM\ManyToOne(targetEntity: Localitzacio::class)]
    #[ORM\JoinColumn(name: 'venue_id', referencedColumnName: 'id')]
    private $venue;

    #[ORM\Column(type: 'string', length: 255)]
    private  $imagen;

    #[ORM\Column(type: 'string', length: 255)]
    private  $artist;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStartTime(): \DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function getEndTime(): \DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): void
    {
        $this->endTime = $endTime;
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

    public function getImage(): ?string
    {
        return $this->imagen;
    }

    public function setImage(?string $image): void
    {
        $this->imagen = $image;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(?string $artist): void
    {
        $this->artist = $artist;
    }
}