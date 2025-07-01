<?php

class Theme {
    private $id_tema;
    private $nom;
    private $numFrases; //lo mismo que en Author, añado este campo para almacenar el numero de frases del theme
    // desde el modelo
    
    
    public function __construct($nom, $id_tema = null, $numFrases=0) {
        $this->id_tema = $id_tema;
        $this->nom = $nom;
        $this->numFrases = $numFrases; 
    }
    
    
    public function __get($atributo) {
        if (property_exists($this, $atributo)) {
            return $this->$atributo;
        }
    }
    
    public function __set($atributo, $value) {
        if (property_exists($this, $atributo)) {
            $this->$atributo = $value;
        }
    }
}


