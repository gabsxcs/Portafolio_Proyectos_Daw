<?php

class Phrase {
    
    private $id_frase;
    private $texto;
    private $id_autor;
    private $autor; 
    private $temas; 
    //Añado autor y temas porque al crear el objeto en el modelo neceito estos campos para almacenar esa info
    
    public function __construct($id_frase = null, $texto, $id_autor, $autor = "", $temas = "") {
        $this->id_frase = $id_frase;
        $this->texto = $texto;
        $this->id_autor = $id_autor;
        $this->autor=$autor;
        $this->temas=$temas;
    }
    
    public function __get($atributo) {
        return property_exists($this, $atributo) ? $this->$atributo : null;
    }
    
    public function __set($atributo, $value) {
        if (property_exists($this, $atributo)) {
            $this->$atributo = $value;
        }
    }
}
