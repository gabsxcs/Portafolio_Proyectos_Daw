<?php

class Http {
    private static $controller;
    private static $action;
    private static $params;
    
    public static function init($controller_name, $action, $params) {
        if (file_exists(__ROOT__."classes/controller/{$controller_name}Controller.php")) {
            $classe = $controller_name."Controller";
            self::$controller = new $classe();
            if (method_exists(self::$controller, $action)){
                self::$action = $action;
                self::$params = $params;
            } else {
                throw new Exception("no existeix l'acció definida de $controller_name");
            }
        } else {
            throw new Exception("no existeix la definició de $controller_name");
        }
    }
     
    public static function get(){
        $acc = self::$action;
        self::$controller->$acc(self::$params);
    }
    
    public static function post(){
        self::$controller->form(self::$params);
    }
}

    



