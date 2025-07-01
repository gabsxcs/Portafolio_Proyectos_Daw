<?php
namespace Frases\Repository;

use Doctrine\ORM\EntityRepository;
use Frases\Entity\Author;
use Frases\Entity\Phrase;
use Frases\Entity\Theme;

class PhraseRepository extends EntityRepository{
    
    
    
    //creaa una frase
    public function createPhrase(\Frases\Entity\Phrase $phrase) {
        $entityManager = $this->getEntityManager();
      
        $entityManager->persist($phrase);
        $entityManager->flush();
        
        return true;
    }
    
    
    //obtener frase con su id
    public function getPhraseById($id) {
        $dql = 'SELECT p, a, t
            FROM Frases\Entity\Phrase p
            LEFT JOIN p.author a
            LEFT JOIN p.themes t
            WHERE p.id = :id';
        
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('id', $id);
        
        $phrase = $query->getOneOrNullResult();
        
        if (!$phrase) {
            return null;
        }
        
        return $phrase;
    }
    
    //Actualizar Phrase
    public function updatePhrase(Phrase $phrase) {
        $entityManager = $this->getEntityManager();
        
        $fraseExistente = $this->find($phrase->getId());

        if (!$fraseExistente) {
            return "Error: La frase no existe."; 
        }


        $fraseExistente->setTexto($phrase->getTexto());
        $fraseExistente->setAuthor($phrase->getAuthor());

        
        $nuevoTemaId = null;
        if (!$phrase->getThemes()->isEmpty()) {
            $nuevoTema = $phrase->getThemes()->first(); 
            $nuevoTemaId = $nuevoTema->getId(); 
        }

        $fraseExistente->getThemes()->clear();


        if ($nuevoTemaId) {
            $theme = $entityManager->getRepository(Theme::class)->find($nuevoTemaId);
            if ($theme) {
                $fraseExistente->addTheme($theme);
            } 
        }
        $entityManager->flush();
        return true;
    }
    
    
    
    //Brorar la frase y su relacion en tbl_frases_temas
    public function deletePhrase($id) {
        $entityManager = $this->getEntityManager();
        
        $phrase = $this->find($id);
        
        if (!$phrase) {
            return false; 
        }
        
        $entityManager->remove($phrase);
        $entityManager->flush();
        
        return true;
    }
    
    //Obtiene todas las frases que hayan
    public function getAllPhrases() {
        $query = $this->createQueryBuilder('p')
        ->select('p.id', 'p.texto', 'a.name as author', 't.name as theme')
        ->join('p.author', 'a')  
        ->leftJoin('p.themes', 't') 
        ->getQuery();
        
        
        $results = $query->getArrayResult();
        
        
        $phrases = [];
        foreach ($results as $row) {
            $id = $row['id'];
            
           
            if (!isset($phrases[$id])) {
                $phrases[$id] = [
                    'id' => $id,
                    'texto' => $row['texto'],
                    'author' => $row['author'], 
                    'themes' => [] 
                ];
            }
            
          
            if ($row['theme']) {
                $phrases[$id]['themes'][] = $row['theme'];
            }
        }
        
      
        return $phrases;
    }
   
    
    
    //Obtener el numero total de frases, para que el controller haga el calculo para la paginacion de la tabla
    public function getTotalPhrases($busqueda = '') {
        $busqueda = "%" . $busqueda . "%";
        
        $queryBuilder = $this->createQueryBuilder('p')
        ->select('COUNT(DISTINCT p.id)')
        ->leftJoin('p.author', 'a')
        ->leftJoin('p.themes', 't')
        ->where('p.texto LIKE :search')
        ->orWhere('a.name LIKE :search')
        ->orWhere('t.name LIKE :search')
        ->setParameter('search', $busqueda);
        
        $result = $queryBuilder->getQuery()->getSingleScalarResult();
        
        return $result;
    }
    
    
    //inserta frase de xml
    public function insertPhrase(Phrase $phrase) {
        
        $entityManager = $this->getEntityManager();
        
        $entityManager->persist($phrase);
        
        $entityManager->flush();
        
        return $phrase->getId();
    }
    
    //obtiene frases filtradas y dentro de unos limites
    public function getFilteredPhrases($search, $offset, $limit) {
        $searchTerm = "%" . $search . "%";
        
        $dql = "SELECT p FROM Frases\Entity\Phrase p
            LEFT JOIN p.author a
            LEFT JOIN p.themes t
            WHERE p.texto LIKE :search
            OR a.name LIKE :search
            OR t.name LIKE :search";
        
        $query = $this->getEntityManager()->createQuery($dql)
        ->setParameter('search', $searchTerm)
        ->setFirstResult($offset)
        ->setMaxResults($limit);
        
        $result = $query->getResult();
        
        return $result; 
    }
    
    
    //Obtener las frases de un autor por su id
    public function getPhrasesByAuthorId(int $idAutor){
        $query = $this->createQueryBuilder('p')
        ->select('p.id', 'p.texto', 'a.name as author', 't.name as theme')
        ->join('p.author', 'a')
        ->leftJoin('p.themes', 't')
        ->where('a.id = :idAutor')
        ->setParameter('idAutor', $idAutor)
        ->getQuery();
        
        $results = $query->getArrayResult();
        
        $phrases = [];
        foreach ($results as $row) {
            $id = $row['id'];
            
            if (!isset($phrases[$id])) {
                $phrases[$id] = [
                    'id' => $id,
                    'texto' => $row['texto'],
                    'author' => $row['author'],
                    'themes' => []
                ];
                
            }
            
            if ($row['theme']) {
                $phrases[$id]['themes'][] = $row['theme'];
            }
        }
        
        return $phrases;
    }
    
    //obtiene las frases por un tema con su id
    public function getPhrasesByThemeId(int $themeId) {
        return $this->createQueryBuilder('p')
        ->join('p.themes', 't')
        ->where('t.id = :themeId')
        ->setParameter('themeId', $themeId)
        ->setMaxResults(100)
        ->getQuery()
        ->getResult(); 
    }
    
    
}

