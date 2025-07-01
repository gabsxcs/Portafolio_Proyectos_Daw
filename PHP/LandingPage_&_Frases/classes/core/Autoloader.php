<?php
class Autoloader {
    
    private $carpetes = [];
    
    public function __construct() {
        $this->carpetes = $this->obtenerCarpetas(__ROOT__ . 'classes');
    }
    
    public function register() {
        spl_autoload_register([$this, 'autoload']);
        spl_autoload_register([$this, 'autoloadWithClass']);
    }
    
    private function autoload($class) {
        foreach ($this->carpetes as $carpeta) {
            $file = "$carpeta/$class.php";
            if (file_exists($file)) {
                include $file;
                return;
            }
        }
    }
    
    private function autoloadWithClass($class) {
        foreach ($this->carpetes as $carpeta) {
            $file = "$carpeta/$class.class.php";
            if (file_exists($file)) {
                include $file;
                return;
            }
        }
        throw new Exception("Definició de classe no trobada: $class");
    }
    
    private function obtenerCarpetas($directorio) {
        $carpetas = [$directorio];
        foreach (scandir($directorio) as $item) {
            if ($item !== '.' && $item !== '..' && is_dir("$directorio/$item")) {
                $carpetas[] = "$directorio/$item";
            }
        }
        return $carpetas;
    }
}

?>