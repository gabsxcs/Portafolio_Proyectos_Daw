<?php
class View {
   
    protected $idiomaActiu;
    protected $file;
    
    public function __construct() {
        $this->idiomaActiu = (isset($_COOKIE['lang'])) ? $_COOKIE["lang"] : "cat";
        $this->file = "../langs/vars_{$this->idiomaActiu}.php";
    }
    
}
?>