<?php

namespace src\controller;

class Connection{

    public function set_details($user_id,$to_id){

        $this->set_connect_to_id($to_id);
        $this->set_user_id($user_id);
    }

    public function add_connection(){

        if($_SERVER["REQUEST_METHOD"] == "POST"){

            $user_id = $_POST["connect_from_id"];
            $connect_to_id = $_POST["connect_to_id"];

            $query1 = "INSERT INTO connections(user_id,connect_to_id) VALUES($user_id,$connect_to_id)";
            $query2 = "UPDATE users SET first_login = 0 WHERE id = $user_id ";
            $result = \app\App::get_db()->execute_transaction([$query1,$query2]);

            if(!$result){

                $_SESSION["first_login"] = 0;
                return \app\App::get_view()->render("general\unsuccessfull_connection",null,false,false);
            }

            return \app\App::get_view()->render("general\successfull_connection",null,false,false);
    }
    }

    public function is_open(){
        $query = "SELECT * FROM open_connection";
        $result = \app\App::get_db()->read($query);

        $result = $result->fetch(\PDO::FETCH_ASSOC);
        if($result){
            return (bool)$result["open"];
        }
        return false;
    }
    public function open(){

        if($_SERVER["REQUEST_METHOD"] == "GET" && $this->is_open()== false){

            $query1 = "INSERT INTO open_connection(user_id,open) VALUES(".\app\App::get_id().",1)";
            $query2 = "UPDATE users SET main_admin=1 WHERE id =". \app\App::get_id();
            $query3 = "UPDATE users SET first_login=0 WHERE id =". \app\App::get_id();

            $result = \app\App::get_db()->execute_transaction([$query1,$query2,$query3]);

            if($result){
                $_SESSION["main_admin"] = 1;
                echo "connections are now open. since you opened the connection, you are now the main admin";
                echo "<a href='/cms/admin/'><button>Go back to admin area</button></a>";

            }else{

                echo "there was an error, please try it again some other time";
            }

        }
    }

    public function get_user_connections($user_id){

        $query = "SELECT user_id,connect_to_id FROM connections WHERE user_id = :user_id OR connect_to_id = :connect_to_id";
        $result = \app\App::get_db()->read($query,["user_id"=>\app\APP::get_id(),"connect_to_id"=>\app\APP::get_id()]);

        $result = $result->fetchAll(\PDO::FETCH_ASSOC);

        return $result;

    }
}