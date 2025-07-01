<?php
namespace Frases\Controller;

use Frases\View\HomeView;

class HomeController {
    
    public function __construct() {}
    
    public function show() {
        $vHome = new HomeView();
        $vHome->show();
    }
  

}

?>
