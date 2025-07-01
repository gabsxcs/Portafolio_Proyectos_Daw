<?php

class HomeController extends Controller {
    
    public function __construct() {}
    
    public function show() {
        $vHome = new HomeView();
        $vHome->show();
    }
  

}

?>
