<?php

namespace Evento\Repository;

use Evento\Entity\Ticket;
use Doctrine\ORM\EntityRepository;


class TicketRepository extends EntityRepository{


    /**
     * Crear ticket
     * @param Ticket $ticket
     * @return void
     */
    public function create(Ticket $ticket) {
       $this->getEntityManager()->persist($ticket);
        $this->getEntityManager()->flush();
    }

    /**
     * Actualizar ticket
     */
    public function update(){
        $this->getEntityManager()->flush();
    }

    /**
     * 
     * Eliminar ticket
     * @param Ticket $ticket
     */
    public function delete(Ticket $ticket){
        $this->getEntityManager()->remove($ticket);
        $this->getEntityManager()->flush();
    }

    /**
     * Encontrar ticket por su codigo
     * @param string $code
     */
    public function findByCode(string $code){
        return $this->findOneBy(['code' => $code]);
    }

    /**
     * Encontrar por estado del ticket
     * @param string $status
     */
    public function findByStatus(string $status): array
    {
        return $this->findBy(['status' => $status]);
    }

   

}
