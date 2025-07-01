<?php

namespace Frases\Repository;

use Doctrine\ORM\EntityRepository;
use Frases\Entity\Theme;

class ThemeRepository extends EntityRepository{
    
    
    //obtener el tema por su nombre
    public function getThemeByName($nombre){
        return $this->findOneBy(['name' => $nombre]);
    }
    
    
    // Obtener un tema por su ID
    public function getThemeById($id){
        return $this->find($id);
    }
    
    
    ///insertar un tema
    public function createTheme(Theme $theme)
    {
        if (empty($theme->getName())) {
            throw new \Exception("El nombre del tema no puede estar vacío.");
        }
        
        $this->getEntityManager()->persist($theme);
        $this->getEntityManager()->flush();
        
        return $theme->getId();
    }
    
    
    //Elimina el tema junto con sus relaciones en tbl_frases_temas
    public function deleteTheme($id){
        $theme = $this->getEntityManager()->find(\Frases\Entity\Theme::class, $id);
        
        if (!$theme) {
            throw new \Exception("El tema con ID $id no existe.");
        }
        
        foreach ($theme->getPhrases() as $phrase) {
            $phrase->removeTheme($theme);
        }
        
        $this->getEntityManager()->remove($theme);
        $this->getEntityManager()->flush();
        
        return true;
    }
    
    // Obtener todos los temas con la cantidad de frases que tiene cada uno
    public function getThemesWithPhraseCount(){
        $dql = "
        SELECT t, COUNT(p.id) AS phraseCount FROM Frases\Entity\Theme t
        LEFT JOIN t.phrases p  GROUP BY t.id ORDER BY t.name ASC ";
        
        $query = $this->getEntityManager()->createQuery($dql);
        $result = $query->getResult();
        
        $themes = [];
        
        foreach ($result as $row) {
            $theme = $row[0];
            $count = $row['phraseCount'];
            
            $themes[] = [
                'id' => $theme->getId(),
                'name' => $theme->getName(),
                'phraseCount' => $count
            ];
        }
        
        return $themes;
    }
    
    //Obtiene todos los temas
    public function getAllThemes(){
        $dql = "SELECT t FROM Frases\Entity\Theme t ORDER BY t.name ASC";
        $query = $this->getEntityManager()->createQuery($dql);
        
        return $query->getResult();  
    }
    
    
    //Actualiza un tema
    public function updateTheme(Theme $theme) {
        $nombre = $theme->getName();  
        
        
        $themeToUpdate = $this->getEntityManager()->find(Theme::class, $theme->getId());
        
        if (!$themeToUpdate) {
           return false;
        }
        
        $themeToUpdate->setName($nombre);
        
        $this->getEntityManager()->flush();
        
        
        return true;
    }
    
    
    //Obtiene los temas dentro de unos parametros para la paginacion, y si hay criterio de busqueda tambien los obtiene con esos criterios
    public function getThemesWithLimitAndSearch($busqueda, $offset, $limit){
        $busqueda = '%' . $busqueda . '%';
        $offset = (int)$offset;
        $limit = (int)$limit;
        
        $themes = $this->createQueryBuilder('t')
        ->select('t')  
        ->leftJoin('t.phrases', 'p')
        ->where('t.name LIKE :search')
        ->setParameter('search', $busqueda)
        ->groupBy('t.id')
        ->orderBy('t.name', 'ASC')
        ->setFirstResult($offset)
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
        
        return $themes;
    }
    
    
    //Obtiene la cantidad total de temas para que el controller haga la operacion para hacer la paginacion
    public function getTotalThemes($busqueda = ''){
        $busqueda = '%' . $busqueda . '%';
        
        $qb = $this->createQueryBuilder('t')
        ->select('COUNT(DISTINCT t.id) as total');
        
        if (!empty($busqueda)) {
            $qb->where('t.name LIKE :search')
            ->setParameter('search', $busqueda);
        }
        
        $result = $qb->getQuery()->getSingleScalarResult();
        
        return (int)$result;
    }
    
    
}

