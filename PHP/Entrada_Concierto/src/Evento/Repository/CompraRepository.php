<?php

namespace Evento\Repository;

use Doctrine\ORM\EntityRepository;
use Evento\Entity\Compra;

class CompraRepository extends EntityRepository {

    public function create(Compra $compra){
        $this->_em->persist($compra);
        $this->_em->flush();
    }

    public function update() {
        $this->_em->flush();
    }

    public function delete(Compra $compra) {
        $this->_em->remove($compra);
        $this->_em->flush();
    }

    public function findById(int $id) {
        return $this->find($id);
    }

    public function findAllCompras() {
        return $this->findAll();
    }

    public function findComprasPorEmail(string $email) {
        return $this->createQueryBuilder('c')
            ->where('c.emailComprador = :email')
            ->setParameter('email', $email)
            ->orderBy('c.fechaCompra', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findComprasConEntradas() {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.entradas', 'e')
            ->addSelect('e')
            ->getQuery()
            ->getResult();
    }
}
