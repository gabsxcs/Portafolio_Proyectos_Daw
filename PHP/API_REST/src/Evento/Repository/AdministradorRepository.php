<?php

namespace Evento\Repository;

use Evento\Entity\Administrador;
use Doctrine\ORM\EntityRepository;

class AdministradorRepository extends EntityRepository{
    
    /**
     * Crear administrador
     * @param  $administrador
     */
    public function create(Administrador $administrador){
        $this->getEntityManager()->persist($administrador);
        $this->getEntityManager()->flush();
    }

    /**
     * Actualizar administrador
     */
    public function update(){
        $this->getEntityManager()->flush();
    }

    /**
     * Eliminar administrador
     * @param  $administrador
     */
    public function delete(Administrador $administrador) {
        $this->getEntityManager()->remove($administrador);
        $this->getEntityManager()->flush();
    }

    /**
     * Encontrar por email
     * @param string $email
     */
    public function findByEmail(string $email) {
        return $this->findOneBy(['email' => $email]);
    }
}