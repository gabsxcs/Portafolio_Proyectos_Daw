<?php

class Database {
    private static $_instance;
    private $configuracio;
    private $sgbd = "mysql";
    private $link;
    
    private function __construct() {
        $this->configuracio = Config::getInstance();
        
        switch ($this->sgbd){
            case "mysql":
                $this->link = new mysqli(
                $this->configuracio->__get('host'),
                $this->configuracio->__get('username'),
                $this->configuracio->__get('password'),
                $this->configuracio->__get('db_name')
                );
                if ($this->link->connect_error) {
                    die("Error de conexión MySQLi: " . $this->link->connect_error);
                }
                break;
                
            case "sqlserver":
                $this->link = sqlsrv_connect($this->configuracio->__get('host'));
                if (!$this->link) {
                    die("Error de conexión SQL Server");
                }
                break;
                
            case "pdo":
                try {
                    $dsn = "mysql:host=" . $this->configuracio->__get('host') . ";dbname=" . $this->configuracio->__get('db_name') . ";charset=utf8";
                    $this->link = new PDO(
                        $dsn,
                        $this->configuracio->__get('username'),
                        $this->configuracio->__get('password'),
                        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                        );
                } catch (PDOException $e) {
                    die("Error de conexión PDO: " . $e->getMessage());
                }
                break;
        }
    }
    
    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function execute($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        
        if ($this->sgbd === "mysql") {
            return $stmt->affected_rows > 0;
        } elseif ($this->sgbd === "pdo") {
            return $stmt->rowCount() > 0;
        }
    }
    
    public function query($sql, $params = []) {
        if ($this->sgbd === "mysql") {
            
            $stmt = $this->link->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta MySQL: " . $this->link->error);
            }
            
            if (!empty($params)) {
                if (!is_array($params)) {
                    throw new Exception("Error: Los parámetros deben ser un array.");
                }
                
                $types = str_repeat('s', count($params)); 
                $stmt->bind_param($types, ...$params);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Error en la ejecución de la consulta MySQL: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            if ($result === false) {
                return null;
            }
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            
            $stmt->close();
            return $data;
        } elseif ($this->sgbd === "pdo") {
            $stmt = $this->link->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta PDO: " . implode(", ", $this->link->errorInfo()));
            }
            
            if (!is_array($params)) {
                throw new Exception("Error: Los parámetros deben ser un array.");
            }
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(is_int($key) ? ($key + 1) : $key, $value);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Error en la ejecución de la consulta PDO: " . implode(", ", $stmt->errorInfo()));
            }
            
            if (stripos(trim($sql), 'SELECT') === 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $stmt;
        }
    }
    
    public function lastInsertId() {
        if ($this->sgbd === "mysql") {
            return $this->link->insert_id; // MySQLi
        } elseif ($this->sgbd === "pdo") {
            return $this->link->lastInsertId(); // PDO
        }
    }
    
    
    public function close() {
        if ($this->sgbd === "mysql" && $this->link) {
            $this->link->close();
        }
        self::$_instance = null;
    }
    
    private function __clone() {}
}
?>
