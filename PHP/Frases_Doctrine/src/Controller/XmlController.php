<?php
namespace Frases\Controller;


use Doctrine\ORM\EntityManager;
use Frases\Repository\XmlRepository;
use Frases\View\XmlView;
use Doctrine\ORM\Tools\SchemaTool;

class XmlController{
    private $entityManager;
    private $xmlRepository;
    private $schemaTool;
    
   
    public function __construct(EntityManager $entityManager){
        $this->entityManager = $entityManager;
        $this->xmlRepository = new XmlRepository($entityManager);
        $this->schemaTool = new SchemaTool($entityManager);
    }
    
    
    /**
     * Muestra la view de la pagina en donde se cargan los datos
     */
    public function show(){
      
        
        $view = new XmlView();
        $view->show();
    }
    
    /**
     * Primero crea las tablas y luego hace los inserts
     */
    public function processXmlAction(){
        $xmlUrl = 'Xml/frases.xml';
        
        try {
            $this->xmlRepository->creaTablasBaseDatos();
            
            $this->xmlRepository->processXml($xmlUrl);
           
            header("Location: Index.php?Phrases/show");
            exit();
        } catch (\Exception $e) {
            return "Error al procesar el archivo XML y meterlo en la base de datos: " . $e->getMessage();
        }
    }
    
    /**
     * Borra las tablas de la bbdd
     */
    public function borrarTablas(){
      
        
        $this->xmlRepository->eliminarTablasBaseDatos();
        header("Location: Index.php?Xml/show");
        exit();
    }
    
}
