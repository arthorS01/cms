<?php

declare(strict_types = 1);

namespace src\controller;

class Router{

    private $routes = [];
    private $accepted_methods = ["GET","POST","UPDATE","DELETE"];
 
    public function get($path,array $controller):self{

        $this->register($path,$controller,"GET");

        return $this;
    }

    private function register(String $path, array $controller,$method){

        $this->routes[$method][$path] = $controller;
    }

    public function render(String $uri,String $method){

        
            $components = parse_url($uri);

            $path = $components["path"];
            $components = explode("/",$path);

            $controller = $components[2] ?? null;
            $action = $components[3] ?? null;

            $path = $controller."/".$action;


            if(isset($this->routes[$method][$path])){

            $controller_details = $this->routes[$method][$path];
            $class = $controller_details[0];
            $controller_method = $controller_details[1];
            $obj = new $class();

            return call_user_func_array([$obj,$controller_method],[]);
            }{
              $obj = new Home;
              return $obj->route_not_found();
            }
        
    }

    public function post(String $path,array $controller){
        
        $this->register($path,$controller,"POST");

        return $this;
    }

    public function update(String $path,array $controller){
        
        $this->register($path,$controller,"UPDATE");

        return $this;
    }

    public function delete(String $path,array $controller){
        
        $this->register($path,$controller,"DELETE");

        return $this;
    }
    //testing
    public function get_registered_paths(){
        print_r($this->routes);
    }
}