<?php

namespace Evento\Entity;

use Doctrine\ORM\Mapping as ORM;
use Evento\Repository\AdministradorRepository;

#[ORM\Entity(repositoryClass: AdministradorRepository::class)]
#[ORM\Table(name: 'tbl_administrador')]
class Administrador {
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private  $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private  $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private  $email;

    #[ORM\Column(type: 'string', length: 255)]
    private  $contrasenya;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
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
}