<?php

declare(strict_types = 1);

namespace src\controller;

use \app\DataChecker;
use \app\App;

class User{

    private $user_model;
    
    public function __construct(){

        $this->user_model = new \src\model\User();
    }

    public function create(){

       
        if($_SERVER["REQUEST_METHOD"] == "POST"){

           
            $data = file_get_contents("php://input");
            $data = JSON_decode($data,true);

            foreach($data as $key=>$entry){

                $data[$key] = DataChecker::sanitize($data[$key]);
            }

            if(($result = DataChecker::check_name($data["fname"],"firstname")) !== true){

                $response["type"] = "error";
                $response["message"] = $result;

            }elseif(($result = DataChecker::check_name($data["lname"],"lastname")) !== true){

                $response["type"] = "error";
                $response["message"] = $result;

            }elseif(($result = DataChecker::check_password($data["passw"])) !== true){

                $response["type"] = "error";
                $response["message"] = $result;

            }else{

                $data["passw"] = password_hash($data["passw"],PASSWORD_BCRYPT);
                $result = (new \src\model\User)->create($data);
                if($result){
                    $response = [
                        "type"=>"confirm",
                        "message"=>"success"
                    ];
        
                }else{
                    $response = [
                        "type"=>"error",
                        "message"=>"failed to create user"
                    ];
                }
            }
            return JSON_encode($response);
        }

       
       
    }

    public function login(){

        $response = [
            "type"=>"confirm",
            "message"=>"login-successful"
        ];

        if($_SERVER["REQUEST_METHOD"] == "POST"){

           
            $data = file_get_contents("php://input");
            $data = JSON_decode($data,true);

            foreach($data as $key=>$entry){

                $data[$key] = DataChecker::sanitize($data[$key]);
            }

    
            $result = (new \src\model\User)->login(["email"=>$data["udata"]]);
      
            if($result && password_verify($data["passw"],$result["password"])){

               
                $_SESSION["user_id"] = $result["id"];
                $_SESSION["acc_type"] = $result["type"];
                $_SESSION["fname"] = $result["firstname"];
                $_SESSION["lname"] = $result["lastname"];
                $_SESSION["email"] = $result["email"];
                $_SESSION["first_login"] = $result["first_login"];
                $_SESSION["main_admin"] = $result["main_admin"];
                $_SESSION["background_image"] = "sun.png" ;
                $response["redirect"] = "http://localhost/cms/create_connection";

                if($_SESSION["acc_type"] == "student"){
                    $_SESSION["to_id"] = $this->get_connection();
                }
                }else{
                $response = [
                    "type"=>"error",
                    "message"=>"incorrect email or password"
                ];
            }
        }
        return JSON_encode($response);
    }

    public function log_out(){

       // $response = [
         //   "type"=>"confirm",
         //   "message"=>"logout was successful"
       // ];

        if(isset($_SESSION["acc_type"]) && isset($_SESSION["user_id"])){

            $id = $_SESSION["user_id"];

           if(!session_destroy()){

                http_response_code(500);

                $response = [
                    "type"=>"error",
                    "message"=>"failed to logout",
                ];
                
           }else{
            //$this->update_active_status($id,0);
            header("location:http://localhost/cms/");
           }

    }
}

public function connect(){


    if(\app\App::get_connection()->is_open()){

        
        if($_SESSION["main_admin"] == 0){
            
            
            if(\app\App::compare_session("first_login",1)){
                
                echo \app\App::get_view()->render("general\create _connection",null,false,false);   
            }else{
                echo "not first login";
                 ($_SESSION["acc_type"] == "admin")? header("location:http://localhost/cms/admin") :header("location:http://localhost/cms/student");
            }

        }else{
        
            echo (new Admin)->index();
        }
    }else{
     
        echo \app\App::get_view()->render("general\open_connections",null,false,false); 
    }
}
public function get_user_by_type($str_type){

    try{
        $users = $this->user_model->get_user_by_type($str_type);
        $connect_from_id = null;
        $html = "<div class='user_container'>";

      
        

        return $users;

    }catch(\Exception $e){

        echo "failed to load admin users";
    }
}

public function get_messages(){

    if($_SERVER["REQUEST_METHOD"] == "GET"){

    
        if(isset($_GET["user_id"]) && isset($_GET["patner_id"])){

           
            $query1 = "SELECT * FROM messages WHERE (user_id = :user_id AND to_id = :to_id) OR (user_id = :to_id AND to_id = :user_id)";
            //$query2 = "SELECT * FROM messages WHERE user_id = :user_id AND to_id = :to_id";

            $result1 = \app\App::get_db()->read($query1,["user_id"=>$_GET["user_id"],"to_id"=>$_GET["patner_id"]]);
            //$result2 = \app\App::get_db()->read($query2,["user_id"=>$_GET["patner_id"],"to_id"=>$_GET["user_id"]]);
            
            $result1 = $result1->fetchAll(\PDO::FETCH_ASSOC);
            //$result2 = $result2->fetchAll(\PDO::FETCH_ASSOC);

            $result = $result1;

            if(empty($result)){
                return JSON_encode([]);
            }
            return JSON_encode($result);

        }else{
            echo "got here-nope";
            return JSON_encode("unsuccessfull");
        }
    }
}

public function get_fullname(){

    if($_SERVER["REQUEST_METHOD"] == "GET"){

        return JSON_encode($this->get_name($_GET["id"]));
    }
}
public function get_name($user_id){

    $query = "SELECT firstname,lastname FROM users WHERE id = :user_id";
    $result = \app\App::get_db()->read($query,["user_id"=>$user_id]);
    $fullname =  $result->fetch(\PDO::FETCH_ASSOC);

    $fullname = array_values($fullname);
    $fullname = implode(" ",$fullname);

    return $fullname;
}

public function get_acc_type($user_id){

    $query = "SELECT type FROM users WHERE id = :user_id";
    $result = \app\App::get_db()->read($query,["user_id"=>$user_id]);
    $acc_type =  $result->fetch(\PDO::FETCH_ASSOC);

    $acc_type = $acc_type["type"];
  

    return $acc_type;
}

public function get_user_image(){

    if($_SERVER["REQUEST_METHOD"] == "GET"){

        return JSON_encode($this->get_image($_GET["id"]));
    }
   

}

public function get_user_chat(){

    

        return JSON_encode($this->get_messages());
    
   

}

public function get_image($user_id){
    $query = "SELECT background_image FROM user_details WHERE user_id = :user_id";
    $result = \app\App::get_db()->read($query,["user_id"=>$user_id]);
    $image=  $result->fetch(\PDO::FETCH_ASSOC);

    $image = $image["background_image"];
  

    return $image;
}

public function get_connection(){

    $query = "SELECT connect_to_id FROM connections WHERE user_id = :id";
    $result = \app\App::get_db()->read($query,["id"=>\app\App::get_id()]);
    $id = $result->fetch(\PDO::FETCH_ASSOC);

    return $id["connect_to_id"];

}
public function get_user_status(){

    return JSON_encode($this->get_status($_GET["id"]));
}

public function get_status($user_id){
    $query = "SELECT online FROM user_details WHERE user_id = :user_id";
    $result = \app\App::get_db()->read($query,["user_id"=>$user_id]);
    $status=  $result->fetch(\PDO::FETCH_ASSOC);

    $status = $status["online"];
  

    return (int)$status;
}

public function update_active_status($id,$status){

    $query = "UPDATE user_details SET online = $status WHERE user_id = :id ";
    $db = App::get_db();
    $result = $db->update($query,["id"=>$id]);
}


public function get_admins(){

    $db = App::get_db();
    $query = "SELECT users.id,users.firstname,users.lastname,user_details.background_image,user_details.online FROM users 
    INNER JOIN user_details ON users.id = user_details.user_id WHERE users.type = 'admin' ";
    $result = $db->read($query);

    return $result->fetchAll(\PDO::FETCH_ASSOC);
}
}