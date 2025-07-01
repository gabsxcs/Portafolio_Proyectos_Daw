<?php
namespace Frases\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\SchemaTool;
use Frases\Entity\Author;
use Frases\Entity\Phrase;
use Frases\Entity\Theme;

class XmlRepository extends EntityRepository{
    private $entityManager;
    private $authors = [];
    private $themes = [];
    private $phrases = [];
    private $schemaTool;
    
    public function __construct(EntityManager $entityManager){
        $this->entityManager = $entityManager;
        $this->schemaTool = new SchemaTool($entityManager);
    }
    
    /**
     * Procesa un archivo XML y lo guarda en la base de datos.
     *
     * @param  $xmlFilePath Ruta del archivo XML 
     * @return 
     */
    public function processXml($xmlFilePath){
        $xml = simplexml_load_file($xmlFilePath);
        
        foreach ($xml->autor as $xmlAuthor) {
            $authorName = (string) $xmlAuthor->nombre;
            $authorDescription = (string) $xmlAuthor->descripcion;
            
            if (!isset($this->authors[$authorName])) {
                
                $author = new Author();
                $author->setName($authorName);
                $author->setDescription($authorDescription);
                
                $this->authors[$authorName] = $author;
            } else {
                $author = $this->authors[$authorName];
            }
            
            foreach ($xmlAuthor->frases->frase as $xmlPhrase) {
                $phraseText = mb_strtolower(trim((string)$xmlPhrase->texto)); 
                $phraseText = preg_replace('/\s+/', ' ', $phraseText);
                $phraseText = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'ü'],
                    ['a', 'e', 'i', 'o', 'u', 'u'],
                    $phraseText
                    ); 
                
                    if (!isset($this->phrases[$phraseText])) {
                        $phrase = new Phrase();
                        $phrase->setTexto($phraseText);
                        $phrase->setAuthor($author); 
                        
                        foreach ($xmlPhrase->tema as $xmlTheme) {
                            $themeName = (string) $xmlTheme;
                            
                            if (!isset($this->themes[$themeName])) {
                                $theme = new Theme();
                                $theme->setName($themeName);
                                
                                $this->themes[$themeName] = $theme;
                            } else {
                                $theme = $this->themes[$themeName];  
                            }
                            
                            $phrase->addTheme($theme);
                        }
                        
                        $this->phrases[$phraseText] = $phrase;
                    }
            }
        }
        
        foreach ($this->authors as $author) {
            $this->entityManager->persist($author);
        }
        
        foreach ($this->themes as $theme) {
            $this->entityManager->persist($theme);
        }
        
        foreach ($this->phrases as $phrase) {
            $this->entityManager->persist($phrase);
        }
        
        $this->entityManager->flush();
    }
    
    /**
     * Elimina las tablas de la base de datos.
     */
    public function eliminarTablasBaseDatos(){
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $this->schemaTool->dropSchema($classes);
    }
    
    /**
     * Crea las tablas en la base de datos según los objetos Author, Phrase y Theme
     */
    public function creaTablasBaseDatos(){
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $this->schemaTool->createSchema($classes);
    }
}

