<?php

declare(strict_types = 1);


namespace src\controller;

use \app\App;


class Admin{

    private $main_admin ;
    private $fname;
    private $lname;

    public function index(){

        $view = App::get_view();
        $redirect = "http://localhost/cms/";

       if(isset($_SESSION["acc_type"]) && isset($_SESSION["user_id"])){

         return $view->render("admin/index",null,false);

       }else{

        header("location:$redirect");
       }

      
        
    }

    public function set_details($main_admin, $fname, $lname){

        $this->main_admin = $main_admin;
        $this->fname = $fname;
        $this->lname = $lname;
        
    }
    /* retuns the admins dashboard view */

    public function dashboard(){
         
        $view = App::get_view();

        return JSON_encode($view->render("admin/dashboard",null,false,false));    

    }

    public function complaint(){
         
        $view = App::get_view();
        $model = new \src\model\Admin;

        $complaints = $model->get_complaints(\app\App::get_id());
        foreach($complaints as $index=>$case){

            if($case["anonymous"] == 1){
                $complaints[$index]["firstname"] = " ";
                $complaints[$index]["lastname"] = " ";
                $complaints[$index]["background_image"] = "fi-cnluxx-anonymous-user-circle.png";
            }
        }
        $db = App::get_db();

        $query = "SELECT referal_request.id,referal_request.case_id,referal_request.receiver_id, user_details.user_id,user_details.background_image, user_details.online FROM referal_request INNER JOIN user_details ON referal_request.receiver_id = user_details.user_id WHERE sender_id = :id";
        $referals = $db->read($query,["id"=>$_SESSION["user_id"]]);
        $referals= $referals->fetchAll(\PDO::FETCH_ASSOC);

        $cases = [
            "sent"=>[],
            "seen"=>[],
            "special"=>[]
        
    ];

        return JSON_encode($view->render("admin/complaint",[$complaints,$referals],false,false));
        

    }

    public function settings(){
         
        $view = App::get_view();

        return JSON_encode($view->render("admin/settings",null,false,false));
        

    }


    public function chat(){
        
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

        //get all chats
        //$user_chats = \app\App::get_user()->get_chats($id);

        return JSON_encode(\app\App::get_view()->render("admin/chat",$connections,false,false));
        
    }

    public function notification(){
         
        $view = App::get_view();
        $db = App::get_db();

        $query = "SELECT * FROM notification WHERE user_id = :id";
        $notifications = $db->read($query,["id"=>$_SESSION["user_id"]]);

        $notifications = $notifications->fetchAll(\PDO::FETCH_ASSOC);

        return JSON_encode($view->render("admin/notification",$notifications,false,false));
        

    }

    public function history(){
         
        $view = App::get_view();
        $history_controller = App::get_history();
        $data = $history_controller->get_history();

    
        return JSON_encode($view->render("general/history",$data,false,false));
        

    }

    public function cancel_request(){

        if($_SERVER["REQUEST_METHOD"] == "UPDATE"){

            return JSON_encode("done");
        }

        http_response_code(404);
    }
}