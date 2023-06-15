<?php


namespace src\view;

class View{

    public function render(string $file, array $param = null, bool $header = true,bool $footer = true){

        $complete_path = __DIR__."\\pages\\".$file.".php";

   
        if(file_exists($complete_path)){

            ob_start();
            if($header == 1){
                require_once __DIR__."\\includes\\header.php";
            }
            require_once $complete_path;
            if($footer == true){
                require_once __DIR__."\\includes\\footer.php";
            }
           

            return ob_get_clean();

        }else{
            echo "$complete_path doesn't exist";
        }
    }
}