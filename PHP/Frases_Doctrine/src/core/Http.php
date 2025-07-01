<?php

namespace Frases\Core;
use Frases\Controller\FrontController;
use Doctrine\DBAL\Exception;

class Http {
    private static $controller;
    private static $action;
    private static $params;
    private static $entityManager;
    
    public static function setEntityManager($em) {
        self::$entityManager = $em;
    }
    
    /**
     * Recibe el entity manager que le envio desde el index
     */
    public static function init($controller_name, $action, $params) {
        if (file_exists(__ROOT__."src/Controller/{$controller_name}Controller.php")) {
            $classe = "Frases\\Controller\\".$controller_name."Controller";
            self::$controller = new $classe(self::$entityManager);
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

    



