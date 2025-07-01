<?php

namespace Evento\Repository;

use Doctrine\ORM\EntityRepository;
use Evento\Entity\Categoria;

class CategoriaRepository extends EntityRepository {

    public function create(Categoria $categoria){
        $this->_em->persist($categoria);
        $this->_em->flush();
    }
 
    public function update(): void {
        $this->_em->flush();
    }

    public function delete(Categoria $categoria) {
        $this->_em->remove($categoria);
        $this->_em->flush();
    }

    public function findById(int $id) {
        return $this->find($id);
    }

    public function findAllCategorias() {
        return $this->findAll();
    }

}
