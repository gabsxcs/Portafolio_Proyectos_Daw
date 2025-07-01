<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

abstract class DBAbstractModel {

    private static $_instance;
    private static $db_host;
    private static $db_user;
    private static $db_pass;
    protected $db_name;
    protected $query;
    protected $rows = array();
    
    private $conn;
    
    // funcion para cargar los datos del archivo XML del usuari generic
    protected  function conseguirDatos() {
        $config = Config::getInstance();
        self::$db_host = $config->__get('host');
        self::$db_user = $config->__get('username');
        self::$db_pass = $config->__get('password');
        $this->db_name = $config->__get('db_name');
    }
    
    // Metodo para cambiar al usuario 'usr_consulta' para hacer las operaciones de SELECT
    protected function change_to_usr_consulta() {
        $this->conn->change_user('usr_consulta', '2025@Thos', $this->db_name);
    }
    
    //Tercer usuario creado para hacer los pudates, ya que con los otros usuarios no pude lograrlo.
    //AL hacer update el usuario necesito permisos tanto para hacer select como para hacer update, asi que los otros dos usuarios
    // ya creados, no me sirven para hacer update, asi que he creado este que tiene permisos de update y select
    protected function change_to_usr_super() {
        $this->conn->change_user('usr_super', 'Super@2025', $this->db_name);
    }
    
    
    

    # mètodes abstractes per a ABM de classes que heretin
    abstract protected function get();
    abstract protected function set();
    abstract protected function edit();
    abstract protected function delete();

    # els següents mètodes poden definir-se amb exactitud
    # i no són abstractes
    # Connectar a la base de dades
    protected function open_connection() {
        self::conseguirDatos();  // Carga los datos de conexión
        $this->conn = new mysqli(self::$db_host, self::$db_user, self::$db_pass, $this->db_name);
        
        if ($this->conn->connect_error) {
            echo "Conexión fallida: " . $this->conn->connect_error;
            $this->conn = null;
        }
    }
    

    # Desconectar la base de dades
    private function close_connection() {
        $this->conn->close();
    }

    # Executar un query simple del tipus INSERT, DELETE, UPDATE
    protected function execute_single_query() {
        $this->open_connection();
        $this->conn->query($this->query);
        $this->close_connection();
    }

    # Portar resultats d'una consulta en un Array
    protected function get_results_from_query() {
        $this->open_connection();
        $this->change_to_usr_consulta();
        $result = $this->conn->query($this->query);
        
        while ($this->rows[] = $result->fetch_assoc());
        
        $result->close();
        $this->close_connection();
        
        array_pop($this->rows);  
       
        return $this->rows;
    }
    
    //funcion para hacer el update con el tercer usuario que hice
    protected function execute_update_with_super() {
        $this->open_connection();
        $this->change_to_usr_super(); 
        $this->conn->query($this->query); 
        $this->affected_rows = $this->conn->affected_rows; 
        $this->close_connection(); 
    }
    
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}


