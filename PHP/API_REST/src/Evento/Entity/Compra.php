<?php
namespace Evento\Entity;
use Doctrine\ORM\Mapping as ORM;

use Evento\Repository\CompraRepository;

#[ORM\Entity(repositoryClass: CompraRepository::class)]
#[ORM\Table(name: 'tbl_compra')]
#[ORM\Index(columns: ['purchaseDate'], name: 'idx_compra_date')]
#[ORM\Index(columns: ['user_id'], name: 'idx_compra_user')]
class Compra {
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    private  $id;

    #[ORM\Column(type: 'datetime')]
    private  $purchaseDate;

    #[ORM\Column(type: 'string', length: 30)]
    private $paymentMethod;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private  $totalAmount;

    #[ORM\ManyToOne(targetEntity: Usuari::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private  $user;
    
    public function getId(): int
    {
        return $this->id;
    }

    public function getPurchaseDate(): \DateTimeInterface
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(\DateTimeInterface $purchaseDate): void
    {
        $this->purchaseDate = $purchaseDate;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getUser(): Usuari
    {
        return $this->user;
    }

    public function setUser(Usuari $user): void
    {
        $this->user = $user;
    }
}