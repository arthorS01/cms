<?php

namespace src\controller;

class Message{

    private $from_id;
    private $to_id;
    private $body;
    private $status;
    private $sent_date;
    private $seen_date;


  
    public function set_details($from_id,$to_id,$body){

        $this->from_id = $from_id;
        $this->to_id = $to_id;
        $this->body = $body;

    }

    public function get_from_id(){

    }

    public function get_to_id(){

    }

    public function get_body(){

    }

    public function set_to_id(int $id){

        $this->to_id = $id;
    }

    public function update_status(){

    }

    public function send(){

        $db = \app\App::get_db();
        $query = "INSERT INTO messages (user_id,to_id,body) VALUES(:user_id,:to_id,:body)";
        $result = $db->create($query,["user_id"=>$this->from_id,"to_id"=>$this->to_id,"body"=>$this->body]);

        if($result){
            return true;
        }
    }

    public function available_unread(){

        $db= \app\App::get_db();
        $query = "SELECT id FROM messages WHERE to_id = :id AND status='sent' ";
        $result = $db->read($query,["id"=>\app\App::get_id()]);

        //print_r($result->fetch(\PDO::FETCH_ASSOC));
        $result = $result->fetch(\PDO::FETCH_ASSOC);
        if($result){
            return true;
        }
        return false;
    }

    public function get_unread_count($id){
         $db= \app\App::get_db();
        $query = "SELECT body FROM messages WHERE to_id = :id AND status='sent' AND user_id = $id ORDER BY date_sent ASC";
        $result = $db->read($query,["id"=>\app\App::get_id()]);

        $result = $result->fetchAll(\PDO::FETCH_ASSOC);
        if($result){
            $count = count($result);
            $this->last_message = $result[0]["body"];
            return $count;
        }

        return 0;
    }

    public function get_last_message($id){
        $db= \app\App::get_db();
        $query = "SELECT body FROM messages WHERE to_id = :id AND user_id = $id ORDER BY id DESC LIMIT 1";
        $result = $db->read($query,["id"=>\app\App::get_id()]);
        $result = $result->fetch(\PDO::FETCH_ASSOC);

        if($result){
            return $result["body"];
        }
       
    }
}