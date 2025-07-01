<?php

namespace Evento\Repository;

use Doctrine\ORM\EntityRepository;
use Evento\Entity\Entrada;
use Evento\Entity\Compra;

class EntradaRepository extends EntityRepository {

    public function create(Entrada $entrada) {
        $this->_em->persist($entrada);
        $this->_em->flush();
    }

    public function update() {
        $this->_em->flush();
    }

    public function delete(Entrada $entrada) {
        $this->_em->remove($entrada);
        $this->_em->flush();
    }

    public function findById(int $id) {
        return $this->find($id);
    }

    public function findAllEntradas() {
        return $this->findAll();
    }

    public function findEntradasPorEvento(int $eventoId) {
        return $this->createQueryBuilder('e')
            ->where('e.evento = :eventoId')
            ->setParameter('eventoId', $eventoId)
            ->orderBy('e.seccion', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findDisponiblesPorEvento(int $eventoId) {
        return $this->createQueryBuilder('e')
            ->where('e.evento = :eventoId')
            ->andWhere('e.estado = :estado')
            ->setParameter('eventoId', $eventoId)
            ->setParameter('estado', 'disponible')
            ->getQuery()
            ->getResult();
    }

    public function asignarEntradasAColeccionCompra(Compra $compra, array $entradas) {
        foreach ($entradas as $entrada) {
            $entrada->setEstado('activa'); 
            $entrada->setCompra($compra);
            $this->create($entrada); 
        }
    }

    public function findEntradaConDatosRelacionados($codigo){
        return $this->createQueryBuilder('e')
            ->leftJoin('e.evento', 'ev')
            ->leftJoin('e.categoria', 'cat')
            ->addSelect('ev', 'cat')
            ->where('e.codigoReferencia = :codigo')
            ->setParameter('codigo', $codigo)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
}
