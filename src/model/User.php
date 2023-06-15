<?php

declare (strict_types = 1);

namespace src\model;
use app\App;

class User{


    private $db;

    public function __construct(){

        $this->db = App::get_db();
    }

    public function create(array $param){

        $query = "INSERT INTO users(type,firstname,lastname,email,password) VALUES(:account_type,:fname,:lname,:email,:passw)";

        $result = $this->db->create($query,$param);

        return $result;
    }

    public function login(array $param){

       $query = "SELECT * FROM users WHERE (email=:email)";
       $result = $this->db->read($query,$param);

       
        return $result->fetch(\PDO::FETCH_ASSOC);
        
    }

    public function get_user_by_type(string $param){

        $query = "SELECT * FROM users WHERE type=:type";
        $result = App::get_db()->read($query,["type"=>$param]);

        return $result;
    }

    public function get_messages(){

        if($_SERVER["REQUEST_METHOD"] == "GET"){

            $user_id = $_GET["userId"];
            $receiver = $_GET["patnerId"];

            $query = "SELECT * FROM messages WHERE user_id = $user_id AND to_id = $receiver";
            $result = $this->db->read($query);

            return JSON_encode($result);
        }
    }


}