<?php

namespace Evento\Repository;

use Doctrine\ORM\EntityRepository;
use Evento\Entity\Evento;

class EventoRepository extends EntityRepository{

    public function create(Evento $evento){
        $this->_em->persist($evento);
        $this->_em->flush();
    }

    public function update(){
        $this->_em->flush();
    }

    public function delete(Evento $evento){
        $this->_em->remove($evento);
        $this->_em->flush();
    }

    public function findById(int $id){
        return $this->find($id);
    }

    public function findAllEventos(){
        return $this->findAll();
    }

    public function findProximosEventos(){
        $qb = $this->_em->createQueryBuilder();

        $qb->select('e')
            ->from(Evento::class, 'e')
            ->where('e.fecha >= :hoy')
            ->orderBy('e.fecha', 'ASC')
            ->setParameter('hoy', new \DateTime());

        return $qb->getQuery()->getResult();
    }

    public function findEventosDia() {
        $hoy = (new \DateTime())->format('Y-m-d');
    
        return $this->createQueryBuilder('e')
        ->from(Evento::class, 'e')
        ->where('e.fecha = :fecha')
        ->orderBy('e.hora', 'ASC')
        ->setParameter('fecha', $hoy);
    }
    
}
