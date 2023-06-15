<?php

namespace app;

use \src\view\View;
use app\Db;
use \src\controller\{User,Complaint,Connection,Notification,Message,History};

class App{

    private static $view;
    private static $db;
    private static $user;
    private static $complaint;
    private static $connection;
    private static $notification;
    private static $message;
    private static $history;


    public function __construct(){

        self::$view = new View;
        self::$db = new Db;
        self::$user = new User;
        self::$complaint = new Complaint;
        self::$connection = new Connection;
        self::$notification = new Notification;
        self::$message = new Message;
        self::$history = new History;
    

    }

    public static function get_view(){

        return self::$view;
    }

    public static function get_db(){

        return self::$db;
    }

    public static function get_user(){
        return self::$user;
    }

    public static function get_complaint(){

        return self::$complaint;
    }

    public static function get_message(){

        return self::$message;
    }

    public static function get_notification(){
        return self::$notification;
    }
    public static function get_history(){
        return self::$history;
    }
    public static function compare_session($session_str,$value){

        if(isset($_SESSION[$session_str]) && $_SESSION[$session_str]=== $value){

            return true;
    
        }

        return false;
    }
    public static function get_id(){

        if(isset($_SESSION["user_id"])){

            return $_SESSION["user_id"];
        }
    }
    public static function check_session(){

        $redirect = "null";

        if(isset($_SESSION["acc_type"]) && isset($_SESSION["user_id"])){

            switch($_SESSION["acc_type"]){
                case "admin":
                   $redirect = "location:http://localhost/cms/admin/";
                   break;
                case "student":
                    $redirect = "location:http://localhost/cms/student/";
                    break;    
            }

        }else{
            return false;
        }
        
            header("location:$redirect");
    
    }

    public static function get_connection(){

        return self::$connection;
    }

    public function run(\src\controller\Router $router, array $param){

        return $router->render($param[0],$param[1]);
    }


 
}