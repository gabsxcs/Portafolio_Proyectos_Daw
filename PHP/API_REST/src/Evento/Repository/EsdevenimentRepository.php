<?php

namespace Evento\Repository;

use Evento\Entity\Esdeveniment;
use Doctrine\ORM\EntityRepository;

class EsdevenimentRepository extends EntityRepository {

    /**
     * Crear evento
     * @param Esdeveniment $esdeveniment
     */
    public function create(Esdeveniment $esdeveniment){
        $this->getEntityManager()->persist($esdeveniment);
        $this->getEntityManager()->flush();
    }

    /**
     * Actualizar evento
     */
    public function update(){
        $this->getEntityManager()->flush();
    }

    /**
     * Eliminar evento
     * @param Esdeveniment $esdeveniment
     */
    public function delete(Esdeveniment $esdeveniment){
        $this->getEntityManager()->remove($esdeveniment);
        $this->getEntityManager()->flush();
    }

    /**
     * Buscar eventos por tipo
     * 
     * @param string $type
     * @return Esdeveniment[]
     */
    public function findByType(string $type): array
    {
        return $this->findBy(['type' => $type]);
    }

    /**
     * Buscar eventos que empiezan después de una fecha dada
     * 
     * @param \DateTimeInterface $startDate
     * @return Esdeveniment[]
     */
    public function findStartingAfter(\DateTimeInterface $startDate): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.startTime > :startDate')
            ->setParameter('startDate', $startDate)
            ->getQuery()
            ->getResult();
    }

    /**
     * Buscar eventos por fecha exacta (día completo)
     * 
     * @param \DateTimeInterface $date
     * @return Esdeveniment[]
     */
    public function findByDay(\DateTimeInterface $fecha): array
    {
        $inicioDelDia = (clone $fecha)->setTime(0, 0, 0);
        $finDelDia = (clone $fecha)->setTime(23, 59, 59);

        return $this->createQueryBuilder('e')
            ->where('e.startTime BETWEEN :inicio AND :fin')
            ->setParameter('inicio', $inicioDelDia)
            ->setParameter('fin', $finDelDia)
            ->orderBy('e.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }


}
