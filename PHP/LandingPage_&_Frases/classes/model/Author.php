<?php

class Author{
    private $id_autor;
    private $nombre;
    private $descripcion;
    private $url;
    private $numFrases; //añado esta propiedad debido a que como en el modelo creo los objetos que pasaré a controller y luego
    //a la vista para hacer la tabla con los autores, necesito un campo para almacenar la cantidad de frases del autor.
    //aunque este campo no está en mi bbdd
    
    public function __construct($id_autor, $nombre, $descripcion, $url, $numFrases) {
        $this->id_autor = $id_autor;
        $this->nombre=$nombre;
        $this->descripcion=$descripcion;
        $this->url=$url;
        $this->numFrases=$numFrases;
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

