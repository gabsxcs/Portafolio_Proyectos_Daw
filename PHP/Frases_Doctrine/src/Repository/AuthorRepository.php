<?php
namespace Frases\Repository;

use Doctrine\ORM\EntityRepository;
use Frases\Entity\Author;

class AuthorRepository extends EntityRepository{
    // Crear nuevo autor
    public function createAuthor(Author $author) {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($author);
        $entityManager->flush(); 
        return $author->getId();
    }
    
    // Buscar autor por nombre
    public function getAuthorIdByName($name) {
        $qb = $this->createQueryBuilder('a') 
        ->select('a.id') 
        ->where('a.name = :name') 
        ->setParameter('name', $name); 
        
        $result = $qb->getQuery()->getOneOrNullResult(); 
        
        return $result ? $result['id'] : null;
    }
    
    //obtener el autor con su nombre
    public function getAuthorByName($nombre) {
        $qb = $this->createQueryBuilder('a') 
        ->select('a') 
        ->where('a.name = :nombre')
        ->setParameter('nombre', $nombre); 
        
        $result = $qb->getQuery()->getOneOrNullResult(); 
        
        return $result;
    }
    
    
    // Obtener todos los autores
    public function getAllAuthors() {
        $qb = $this->createQueryBuilder('a')
            ->select('a') 
            ->getQuery();
            
        $result = $qb->getResult();
        
        return $result;
    }
    
    
    // Buscar autor por ID
    public function getAuthorById($id) {
        $dql = 'SELECT a FROM Frases\Entity\Author a WHERE a.id = :id';
        
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('id', $id);
        
        $author = $query->getOneOrNullResult(); 
        
        if (!$author) {
            return null; 
        }
        
        return $author;
    }
    
    
    // Actualizar autor (solo si ya se manejó el objeto antes)
    public function updateAuthor(Author $author) {
        $autorExistente = $this->getEntityManager()->find(Author::class, $author->getId());
        
        if ($autorExistente) {
            $autorExistente->setName($author->getName());
            $autorExistente->setDescription($author->getDescription());
            $this->getEntityManager()->flush();
            
            return true;
        }
        
        return false;
    }
    
    
    
    // Eliminar autor
    public function deleteAuthor($author) {
        
        if ($author) {
            $phrases = $author->getPhrases(); 
            foreach ($phrases as $phrase) {
                $this->getEntityManager()->remove($phrase);
            }
            
            $this->getEntityManager()->remove($author);
            $this->getEntityManager()->flush();
            return true;
        }
        
        return false;
    }
    
    
    //borrar las frase relacionadas con autor
    public function deleteAllFrasesTemasByAuthor($id) {
        $author = $this->getEntityManager()->find(Author::class, $id);
        
        if ($author) {
            $frases = $author->getPhrases();
            
            if (!$frases->isEmpty()) {
                foreach ($frases as $frase) {
                    foreach ($frase->getThemes() as $theme) {
                        $frase->removeTheme($theme);
                    }
                    $this->getEntityManager()->remove($frase);
                }
                
                $this->getEntityManager()->flush();
                
                return true;
            }
        }
        
        return false;
    }
    
    
    // Obtener autores y número de frases
    public function getAuthorAndCountFrases() {

        $dql = 'SELECT a, COUNT(f) AS num_frases
            FROM Frases\Entity\Author a
            LEFT JOIN a.phrases f
            GROUP BY a.id';
        
        $query = $this->getEntityManager()->createQuery($dql);
        
        $result = $query->getResult();
        
        $authors = [];
        
       
        foreach ($result as $row) {
            $author = $row[0];
            $numFrases = $row['num_frases']; 
            
            $authors[] = [
                'author' => $author,
                'numFrases' => $numFrases 
            ];
        }
        
        return $authors;
    }
    
    
    //obtener los autores entre unos parametros, es para la paginacion. Ademas hace la busqueda de un autor en concreto
    public function getFilteredAuthors($busqueda, $offset, $limit) {
        $busqueda = "%" . $busqueda . "%";
        
        $dql = "SELECT a
            FROM Frases\Entity\Author a
            WHERE a.name LIKE :search OR a.description LIKE :search
            ORDER BY a.name ASC";  
        
        $query = $this->getEntityManager()->createQuery($dql)
        ->setParameter('search', $busqueda)
        ->setFirstResult($offset)  
        ->setMaxResults(10); 
        
        
        $result = $query->getResult();
        
        
        return $result;
    }
    
    //Obtiene el total de autores
    public function getTotalAuthors($busqueda = '') {
        $busqueda = "%" . $busqueda . "%";
        
        $dql = 'SELECT COUNT(DISTINCT a.id)
            FROM Frases\Entity\Author a
            WHERE a.name LIKE :busqueda OR a.description LIKE :busqueda';
        
        $query = $this->getEntityManager()->createQuery($dql)
        ->setParameter('busqueda', $busqueda);
        
        $totalAuthors = $query->getSingleScalarResult();
        
        return $totalAuthors;
    }
    
    
    
}
