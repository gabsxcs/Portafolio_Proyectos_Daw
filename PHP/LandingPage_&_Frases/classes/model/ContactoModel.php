<?php

class ContactoModel {
    public const FILE = __ROOT__ . "files/datosContacto.xml";
    private $contingut;
    
    public function __construct() {
        $contingut = file_get_contents(self::FILE);
        
        if ($contingut === false || strlen(trim($contingut)) === 0 ) {
            $this->contingut = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<Contactos>\n";
        } elseif (strlen($contingut)>0) {
            $this->contingut = substr($contingut, 0, -12);
        } else {
            $this->contingut = "<Contactos>";
        }
    }
    
    public function set(Contacto $contacte) {
        
        $fechaHora = date('d/m/Y H:i:s'); 
        $contingut = $this->contingut;
        

        $contingut .= " <Contacto>\n";
        $contingut .= "     <data>{$fechaHora}</data>\n";
        $contingut .= "     <nombre>" . html_entity_decode($contacte->__get("nombre")) . "</nombre>\n";
        $contingut .= "     <email>" . html_entity_decode($contacte->__get("email")) . "</email>\n";
        $contingut .= "     <telefono>" . html_entity_decode($contacte->__get("telefono")) . "</telefono>\n";
        $contingut .= "     <asunto>" . html_entity_decode($contacte->__get("asunto")) . "</asunto>\n";
        $contingut .= "     <mensaje>" . html_entity_decode($contacte->__get("mensaje")) . "</mensaje>\n";
        $contingut .= " </Contacto>\n";
        
        $contingut .= "</Contactos>";
        
        if (!file_put_contents(self::FILE, $contingut)) {
            throw new Exception("Problemas de escritura en el archivo de contactos");
        }
    }
    
    
    public function __destruct() {
        
    }
}



?>
