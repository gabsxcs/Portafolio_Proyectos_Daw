<?php

class Contacto {
    private $nombre;
    private $email;
    private $telefono;
    private $asunto;
    private $mensaje;
    
    public $errors;
    
    public function __construct($nombre, $email, $asunto, $mensaje) {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
    }
    
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

