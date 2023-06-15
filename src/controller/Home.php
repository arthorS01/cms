<?php

namespace src\controller;

use \src\view\View;
use \app\App;


class Home{

    public function index(){

        $view = App::get_view();
        
        App::check_session();
           
        return $view->render("general/index");  
    }

    public function login(){

        $view = App::get_view();
        
        App::check_session();

        return $view->render("general/login");  
    }

    public function create_account(){

        $view = App::get_view();

        App::check_session();

        return $view->render("general/createAccount");  
    }

    public function route_not_found(){
        $view = App::get_view();
        http_response_code(404);
        return $view->render("general/404",null,false,false); 
    }

  

 
}