<?php

namespace Evento\Repository;

use Evento\Entity\Usuari;
use Doctrine\ORM\EntityRepository;

class UsuariRepository extends EntityRepository{
    
    /**
     * Crear un usuari
     * @param $usuari el usuari
     */
    public function create(Usuari $usuari){
        $this->getEntityManager()->persist($usuari);
        $this->getEntityManager()->flush();
    }

    /**
     * Actualitzar un usuari
     * @return void
     */
    public function update(){
        $this->getEntityManager()->flush();
    }

    /**
     * Eliminar un usuari
     * @param $usuari el usuari
     */
    public function delete(Usuari $usuari){
        $this->getEntityManager()->remove($usuari);
        $this->getEntityManager()->flush();
    }

    /**
     * Encontrar un usuario por su email
     * @param string $email
     * @return Usuari
     */
    public function findByEmail(string $email){
        return $this->findOneBy(['email' => $email]);
    }
}