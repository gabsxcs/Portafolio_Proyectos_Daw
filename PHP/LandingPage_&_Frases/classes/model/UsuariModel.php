<?php
require_once('DBAbstractModel.php');

class UsuariModel extends DBAbstractModel {

    public $nombre;
    public $apellido;
    public $fechaNacimiento;
    public $genero;
    public $email;
    public $tipoDocumento;
    public $numeroDocumento;
    public $direccion;
    public $codigoPostal;
    public $provincia;
    public $telefono;
    public $fotoUsuario;
    public $esRobot;
    private $password;
    protected $id;
    
    public $errors;

    function __construct() {
        $this->db_name = 'myweb';
    }
    
    public function __get($atributo) {
        if(property_exists($this, $atributo)) {
            return $this->$atributo;
        }
    }
    
    public function __set($atributo, $valor) {
        if(property_exists($this, $atributo)) {
            $this->$atributo = $valor;
        }
    }
    
    public function get($user_email = '') {
        
        if ($user_email != '') {
            $this->query = "
            SELECT id, nombre, email, imagen, contrasena
            FROM usuario
            WHERE email = '$user_email'
        ";
            $this->get_results_from_query();
        }
        
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
        }
    }

    

    public function set($user_data = array()) {
        if (array_key_exists('email', $user_data)) {
            $this->get($user_data['email']);
            if ($user_data['email'] != $this->email) {
                foreach ($user_data as $camp => $valor) {
                    $$camp = $valor;
                }
                
                $this->query = "
    INSERT INTO usuario
    (nombre, apellidos, fechaNacimiento, genero, email, contrasena, tipoDocumento, numeroDocumento, direccion, codigoPostal, provincia, telefono, imagen)
    VALUES
    ('$nombre', '$apellido', '$fechaNacimiento', '$genero', '$email', '$contrasena', '$tipoDocumento', '$numeroDocumento',
    " . (!empty($direccion) ? "'$direccion'" : "NULL") . ",
    " . (!empty($codigoPostal) ? "'$codigoPostal'" : "NULL") . ",
    " . (!empty($provincia) ? "'$provincia'" : "NULL") . ",
    " . (!empty($telefono) ? "'$telefono'" : "NULL") . ",
    'imgUsuarios/$fotoUsuario')
";
                
                                
                $this->execute_single_query();
            }
        }
    }

    public function edit($user_data = array()) {
        foreach ($user_data as $campo=>$valor) {
            $$campo = $valor;
            $this->query = "
                UPDATE usuarios
                SET nombre='$nombre',
                contrasena='$contrasena',
                imagen='$fotoUsuario',
                WHERE email = '$email'
            ";
            $this->execute_single_query();
        }
    }

    public function delete($user_email = '') {
        $this->query = "
             DELETE FROM usuarios
             WHERE email = '$user_email'
             ";
        $this->execute_single_query();
    }
    
    public function verificarUsuario($email, $contrasena) {
        $this->query = "
    SELECT id, email, contrasena, estatus
    FROM usuario
    WHERE email = '$email'
    ";
        $this->get_results_from_query();
        
        if (count($this->rows) == 1) {
            $user = $this->rows[0];
            
            // Esto para que solo los que hayan confirmado el registro en el mail, puedan entrar
            if ($user['estatus'] == 0) {
                return ['error' => 'El registro no ha sido confirmado. Por favor, verifica tu correo electrónico.'];
            }
            
            if (password_verify($contrasena, $user['contrasena'])) {
                return $user; 
            } else {
                return false;
            }
        } else {
            return false; 
        }
    }
    
    
    
    // Metodo para verificar si el correo ya existe, para que no se registre el mismo correo varias veces
    public function verificarEmailExistente($email) {
        $this->query = "
            SELECT id
            FROM usuario
            WHERE email = '$email'
        ";
        $this->get_results_from_query();
        
        return count($this->rows) > 0;
    }
    
    // Metodo para verificar si el numero de documento ya existe, para que no se registre el mismo documento varias veces
    public function verificarDocumentoExistente($numeroDocumento) {
        $this->query = "
            SELECT id
            FROM usuario
            WHERE numeroDocumento = '$numeroDocumento'
        ";
        $this->get_results_from_query();
        
        return count($this->rows) > 0;
    }
    
    //Metodo para que actualizar el status de un usuario
    public function updateStatusById($id) {
        
        if ($id <= 0) {
            $this->errors[] = "ID no válido.";
            return false;
        }
        
        $this->query = "
    UPDATE usuario
    SET estatus = 1
    WHERE id = $id
    ";
        $this->execute_update_with_super();
        return $this->affected_rows > 0;
    }
    
    //Metodo para obtener el id con el email del usuario
    public function getUserIdByEmail($email) {
        $this->query = "
    SELECT id
    FROM usuario
    WHERE email = '$email'
    ";
        $this->get_results_from_query();
        
        if (count($this->rows) > 0) {
            return $this->rows[0]['id']; 
        }
    }
    
    
    

//     public function __destruct() {
//         unset($this);
//     }
}