<?php

namespace Evento\Repository;

use Evento\Entity\Compra;
use Doctrine\ORM\EntityRepository;

class CompraRepository extends EntityRepository {
    /**
     * Crear compra
     * @param Compra $compra
     * @return void
     */
    public function create(Compra $compra) {
       $this->getEntityManager()->persist($compra);
        $this->getEntityManager()->flush();
    }

    /**
     * Actualizar compra
     */
    public function update() {
         $this->getEntityManager()->flush();
    }

    /**
     * Eliminar compra
     * @param Compra $compra
     */
    public function delete(Compra $compra) {
       $this->getEntityManager()->remove($compra);
        $this->getEntityManager()->flush();
    }

    /**
     * Buscar compras por usuario 
     * 
     * @param int $userId
     * @return Compra[]
     */
    public function findByUser(int $userId){
        return $this->findBy(['user' => $userId]);
    }

    /**
     * Buscar compras realizadas después de una fecha determinada
     * 
     * @param \DateTimeInterface $date
     * @return Compra[]
     */
    public function findPurchasesAfterDate(\DateTimeInterface $date) {
        return $this->createQueryBuilder('c')
            ->where('c.purchaseDate > :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }
}
