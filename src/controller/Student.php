<?php

declare(strict_types = 1);


namespace src\controller;

use \app\App;

class Student{

    private $model ;
    private $complaint;


    public function __construct(){

        //$this->model = App::get_student();
        $this->complaint = App::get_complaint();
    }
    
    public function index(){

        $view = App::get_view();
        $redirect = "http://localhost/cms/";

        if(isset($_SESSION["acc_type"]) && isset($_SESSION["user_id"])){

            return $view->render("student/index",null,false);
        }else{

            header("location:$redirect");

           }
        
    }

    /* retuns the students dashboard view */

    public function dashboard(){
         
        $view = App::get_view();

        return JSON_encode($view->render("student/dashboard",null,false,false));
        

    }

    public function complaint(){
         
        $view = App::get_view();
        $db = App::get_db();
        $query = "SELECT complaint.user_id,complaint.to_id,complaint.id,complaint.subject,complaint.detail,complaint.status,complaint.upload_date,users.firstname,users.lastname FROM complaint INNER JOIN users ON complaint.to_id = users.id WHERE complaint.user_id = :user_id";

        $result =$db->read($query,["user_id"=>$_SESSION["user_id"]]);
        
       $data =  $result->fetchAll(\PDO::FETCH_ASSOC);

        return JSON_encode($view->render("student/complaint",$data,false,false));
        

    }

    public function settings(){
         
        $view = App::get_view();

        return JSON_encode($view->render("student/settings",null,false,false));
        

    }

    public function chat(){
         
        $view = App::get_view();

        $user_connections = \app\App::get_connection()->get_user_connections(\app\App::get_id());
        $user_id = \app\App::get_id();
        $connection_id = [];
        $connections = [];
        foreach($user_connections as $connection){

            if($connection["user_id"] !=  $user_id ){
                $connection_id[] = $connection["user_id"]; 
            }else{
                $connection_id[] = $connection["connect_to_id"]; 
            }  
            
        }

        foreach($connection_id as $index=>$id){

            $connections[$index] = [
                "id"=>$id,
                "fullname"=>\app\App::get_user()->get_name($id),
                "acc_type"=>\app\App::get_user()->get_acc_type($id),
                "background_image"=>\app\App::get_user()->get_image($id),
                "online"=>\app\App::get_user()->get_status($id)
            ];

        }

        return JSON_encode($view->render("student/chat",$connections,false,false));
        

    }

    public function notification(){
         
        $view = App::get_view();
        $db = App::get_db();

        
        $query = "SELECT * FROM notification WHERE user_id = :id";
        $notifications = $db->read($query,["id"=>$_SESSION["user_id"]]);

        $notifications = $notifications->fetchAll(\PDO::FETCH_ASSOC);

        return JSON_encode($view->render("student/notification",$notifications,false,false));
        
        

    }

    public function history(){
         
        $view = App::get_view();
        $history_obj = \app\App::get_history();

        $history = $history_obj->get_history();

        return JSON_encode($view->render("student/history",$history,false,false));

    }

    public function make_complaint(){

        if($_SERVER["REQUEST_METHOD"] == "POST"){   
            
           if($this->complaint->make_complaint()){

                return JSON_encode([
                    "status"=>true
                ]);

           }else{

                return JSON_encode($this->complaint->get_error_message());
           }
           

        }

    }

}