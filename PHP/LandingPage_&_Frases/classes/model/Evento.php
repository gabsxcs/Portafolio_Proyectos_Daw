<?php

class Evento{
    
    private $nombre;
    private $descripcion;
    private $inicio;
    private $fin;
    private $todoDia;
    
    public $errors;
    
    
    public function __construct($nombre, $descripcion, $inicio, $fin, $todoDia) {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->inicio = $inicio;
        $this->fin = $fin;
        $this->todoDia = $todoDia;
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

?>