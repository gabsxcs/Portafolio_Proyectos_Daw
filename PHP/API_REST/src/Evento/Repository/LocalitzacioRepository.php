<?php

namespace Evento\Repository;

use Evento\Entity\Localitzacio;
use Doctrine\ORM\EntityRepository;

class LocalitzacioRepository extends EntityRepository {

    /**
     * Crear localizacion
     * @param Localitzacio $localitzacio
     * @return void
     */
    public function create(Localitzacio $localitzacio) {
       $this->getEntityManager()->persist($localitzacio);
        $this->getEntityManager()->flush();
    }

    /**
     * Actualizar localizacion
     */
    public function update() {
        $this->getEntityManager()->flush();
    }

    /**
     * Eliminar localizacion
     * @param Localitzacio $localitzacio
     * @return void
     */
    public function delete(Localitzacio $localitzacio) {
        $this->getEntityManager()->remove($localitzacio);
        $this->getEntityManager()->flush();
    }

    /**
     * Buscar localizaciones por ciudad
     * @param string $ciudad
     */
    public function findByCity(string $ciudad){
        return $this->findBy(['city' => $ciudad]);
    }
}
