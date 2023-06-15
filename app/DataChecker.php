<?php

declare(strict_types = 1);

namespace app;

class DataChecker{

    public static function sanitize(String $data):string{

        $data = trim($data);
        $data = strip_tags($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }


    public static function check_name(String $data,String $field):string|bool{

        $result = true;

           if(preg_match("/^[A-Za-z]+$/",$data)){
               $result = true;
           }else{
            $result = "Your $field should contain only letters";
           }
        return $result;
    }

    public static function check_password(String $data):string|bool{

        $result = true;

            $number = $letters = false;
            $error = null;
            //check for letters
            if(preg_match("/[0-9]/",$data)){
                $number = true;
            }else{
                $error = "Password should contain at least a digit";
            }

            if(preg_match("/[A-Za-z]/i",$data)){
                $letters = true;
            }else{
                $error = "Password should contain at least a letter";
            }

            if(is_null($error)){
                return true;
            
            }

        return $error;
    
    }

    private static function check_int(string $entry){

      
        if(preg_match('/^[0-9]+$/',$entry)){
            return true;
        }

        return false;

    }

    private static function check_special(string $entry){

        if(preg_match('/[^a-zA-Z0-9-]+/',$entry)){

            return true;

    }
    return false;
}

    private static function check_alpha(string $entry){

        if(preg_match('/^[A-Za-z]+$/',$entry)){

            return true;
        }

        return false;
    }
}