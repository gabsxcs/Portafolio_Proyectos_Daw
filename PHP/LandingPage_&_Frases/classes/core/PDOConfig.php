<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class PDOConfig { //config
    private static $_instance;
    
    private $host;
    private $username;
    private $password;
    private $db_name;
    private $port;
    private $socket;
    
    private function __construct() {
        $filename="../config/configPdo.xml";
        if (file_exists($filename)) {
            if ($fitxer = simplexml_load_file($filename)) {
                $this->host = $fitxer->base_de_dades->host->__toString();
                $this->db_name = $fitxer->base_de_dades->db_name->__toString();
                $this->password = $fitxer->base_de_dades->password->__toString();
                $this->username = $fitxer->base_de_dades->username->__toString();
                $this->port = $fitxer->base_de_dades->port->__toString();
                $this->socket = $fitxer->base_de_dades->socket->__toString();
            } else {
                throw new Exception("Fitxer de configuració amb mal format");
            }
        } else {
            throw new Exception("No s'ha pogut obrir el fitxer de configuració");
        }
    }
    
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    private function __clone() {}
    
    public function __get($atributo) {
        if(property_exists($this, $atributo)) {
            return $this->$atributo;
        }
    }
    
    public function __set($atributo, $value){
        if(property_exists($this, $atributo)) {
            $this->$atributo = $value;
        }
    }
    
}

