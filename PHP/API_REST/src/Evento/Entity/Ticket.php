<?php
namespace Evento\Entity;

use Doctrine\ORM\Mapping as ORM;
use Evento\Repository\TicketRepository;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ORM\Table(name: 'tbl_ticket')]
#[ORM\Index(columns: ['status'], name: 'idx_ticket_status')]
#[ORM\Index(columns: ['event_id'], name: 'idx_ticket_event')]
#[ORM\Index(columns: ['price'], name: 'idx_ticket_price')]
class Ticket {
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    private  $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private  $code;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private  $price;

    #[ORM\Column(type: 'string', length: 20)]
    private  $status;

    #[ORM\ManyToOne(targetEntity: Esdeveniment::class)]
    #[ORM\JoinColumn(name: 'event_id', referencedColumnName: 'id')]
    private  $event;

    #[ORM\ManyToOne(targetEntity: Seient::class)]
    #[ORM\JoinColumn(name: 'seat_id', referencedColumnName: 'id')]
    private  $seat;

    #[ORM\ManyToOne(targetEntity: Compra::class)]
    #[ORM\JoinColumn(name: 'purchase_id', referencedColumnName: 'id')]
    private  $purchase;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getEvent(): Esdeveniment
    {
        return $this->event;
    }

    public function setEvent(Esdeveniment $event): void
    {
        $this->event = $event;
    }

    public function getSeat(): Seient
    {
        return $this->seat;
    }

    public function setSeat(Seient $seat): void
    {
        $this->seat = $seat;
    }

    public function getPurchase(): ?Compra
    {
        return $this->purchase;
    }

    public function setPurchase(Compra $purchase): void
    {
        $this->purchase = $purchase;
    }
}