<?php
namespace Evento\Entity;

use Doctrine\ORM\Mapping as ORM;
use Evento\Repository\UsuariRepository;

#[ORM\Entity(repositoryClass: UsuariRepository::class)]
#[ORM\Table(name: 'tbl_usuari')]
#[ORM\Index(columns: ['email'], name: 'idx_usuari_email')] 
#[ORM\Index(columns: ['name'], name: 'idx_usuari_name')]  
#[ORM\Index(columns: ['phone'], name: 'idx_usuari_phone')] 
class Usuari {
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    private  $id;

    #[ORM\Column(type: 'string', length: 100)]
    private  $name;

    #[ORM\Column(type: 'string', length: 150)]
    private  $email;

    #[ORM\Column(type: 'string', length: 30)]
    private  $phone;

    #[ORM\Column(type: 'string', length: 255)]
    private  $contrasenya;

    #[ORM\Column(type: 'datetime')]
    private  $createdAt;


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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getContrasenya(): string
    {
        return $this->contrasenya;
    }

    public function setContrasenya(string $contrasenya): void
    {
        $this->contrasenya = password_hash($contrasenya, PASSWORD_DEFAULT);
    }

    public function verifyContrasenya(string $plainContrasenya): bool
    {
        return password_verify($plainContrasenya, $this->contrasenya);
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}