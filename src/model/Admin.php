<?php

namespace src\model;

class Admin{

    public static function read($query,$param=null){

        return $data->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get_complaints(int $admin_id){

        $query = "SELECT user_details.background_image,user_details.online,users.firstname,users.lastname,complaint.anonymous,complaint.id,complaint.subject,complaint.detail,complaint.status,complaint.anonymous,complaint.upload_date,complaint.user_id
        FROM users INNER JOIN complaint ON users.id = complaint.user_id  INNER JOIN user_details ON user_details.user_id=users.id WHERE complaint.to_id = :admin_id ;";
        $data = \app\App::get_db()->read($query,["admin_id"=>$admin_id]);

        return $data->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function cancel_request($param){
        
    }
}