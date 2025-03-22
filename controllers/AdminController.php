<?php 
class HomeController
{
    public $modelAdmin;

    public function __construct() {
        $this->modelAdmin = new AdminModels();
    }
    
}