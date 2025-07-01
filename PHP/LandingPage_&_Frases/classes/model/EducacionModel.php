<?php

class EducacionModel {
    public function cargarLenguaje($langs) {
        $defaultLang = 'es';
        $langFile = "../langs/vars_{$langs}.php";
        if (file_exists($langFile)) {
            return include($langFile);
        } else {
            return include("../langs/vars_{$defaultLang}.php");
        }
    }
}
?>