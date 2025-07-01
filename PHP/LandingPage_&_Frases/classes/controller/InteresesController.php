<?php

class InteresesController {
    
    public function __construct() {
    }
    
    
    public function show() {
        
        $view = new InteresesView();
        $view->show();
    }
}
?>
