<?php

namespace src\controller;
use \app\App;

class Notification{

    public function delete(){

        if($_SERVER["REQUEST_METHOD"] == "DELETE"){

            $data = JSON_decode(file_get_contents("php://input"),true);
            $db = App::get_db();

            $query = "DELETE FROM notification WHERE id = :id";

            $result = $db->delete($query,$data);

            if($result){

                return  JSON_encode(["status"=>true]);
            }else{
                return JSON_encode(["status"=>false]);
            }

        }
    }

    public function update(){

        if($_SERVER["REQUEST_METHOD"] == "UPDATE"){

            $data = JSON_decode(file_get_contents("php://input"));
            $db = \app\App::get_db();

            $query = "UPDATE notification SET status = 'seen' WHERE id = :id";

            $result = $db->update($query,["id"=>$data->id]);

            if($result){
                return JSON_encode([
                    "status"=>true
                ]);
            }

            return JSON_encode([
                "status">false
            ]);
        }

        header("HTTP/1.1 405 WRONG METHOD");
    }

    public function available_unread(){
        $user_id = App::get_id();

        $db = App::get_db();
        $query = "SELECT * FROM notification WHERE user_id = :user_id AND status = 'unseen' LIMIT 1";
        $result = $db->read($query,["user_id"=>$user_id]);

        if($result){
            return true;
        }else{
            return false;
        }
    }
}