<?php

namespace Evento\Repository;

use Evento\Entity\Seient;
use Doctrine\ORM\EntityRepository;

class SeientRepository extends EntityRepository {

    /**
     * Crear asiento
     * @param Seient $seient
     */
    public function create(Seient $seient) {
        $this->getEntityManager()->persist($seient);
        $this->getEntityManager()->flush();
    }

    /**
     * Actualizar asiento
     */
    public function update() {
        $this->getEntityManager()->flush();
    }

    /**
     * Eliminar asiento
     * @param Seient $seient
     */
    public function delete(Seient $seient) {
        $this->getEntityManager()->remove($seient);
        $this->getEntityManager()->flush();
    }

    /**
     * Encontrar por lugar evento
     * @param int $venueId
     */
    public function findByVenue(int $venueId) {
        return $this->findBy(['venue' => $venueId]);
    }
}
