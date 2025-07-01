<?php

class FrontController {
    private const DEFAULT_CONTROLLER = "Home";
    private const DEFAULT_ACTION = "show";
    
    
    public static function dispatch() { 
        switch ($_SERVER['REQUEST_METHOD']) {
            
            case "GET":
                $params = [];
                if (count($_GET)==0) {
                    $controller_name = FrontController::DEFAULT_CONTROLLER;
                    $action = FrontController::DEFAULT_ACTION;
                    $params=null;
                } else {
                    $url = array_keys($_GET)[0];
                    $url = trim($url,"/");
                    $url = self::sanitize($url,"url");
                    $url = explode("/", $url);
                    if (isset($url[0])) {
                        $controller_name = ucwords($url[0]);
                        if (isset($url[1])) {
                            $action = $url[1];
                        }
                        
                        if (count($url) > 2) {
                            
                            for ($i=2; $i<count($url); $i++) {
                                $params[] = strtolower($url[$i]);
                            }
                        }
                        
                    }
                    
                }
                Http::init($controller_name, $action, $params);
                
                Http::get();
                
                break;
                
            case "POST":
                
                $params = "";
                
                $url = array_keys($_GET)[0];
                
                $url = trim($url,"/");
                
                $url = self::sanitize($url,"url");
                
                $url = explode("/", $url);
                
                if (isset($url[0])) {
                    
                    $controller_name = ucwords($url[0]);
                    
                    if (isset($url[1])) {
                        $action = $url[1];
                    }
                    $params = $_POST;
                }
                Http::init($controller_name, $action, $params);
                
                Http::post();
                break;
                
            case "PUT":
            case "DELETE":
            case "HEAD":
            default:
                throw new Exception("Mètode no suportat");
                break;
        }
        
    }
    
    public static function sanitize($var, $type = "string") {
        $flags = NULL;
        $var = htmlspecialchars(stripslashes(trim($var)));
        switch ($type) {
            case 'url':
                $filter = FILTER_SANITIZE_URL;
                $output = filter_var($var, $filter);
                break;
            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
                $output = filter_var($var, $filter);
                break;
            case 'float':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                $flags = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
                $output = filter_var($var, $filter, $flags);
                break;
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_SANITIZE_EMAIL;
                $flags = FILTER_FLAG_EMAIL_UNICODE;
                $output = filter_var($var, $filter, $flags);
                break;
            case 'string':
            default:
                //$filter = FILTER_SANITIZE_STRING; Deprecated
                $filter = FILTER_SANITIZE_FULL_SPECIAL_CHARS;
                $flags = FILTER_FLAG_NO_ENCODE_QUOTES;
                $output = filter_var($var, $filter, $flags);
                break;
        }
        return ($output);
    }
}

