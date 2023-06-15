<?php
declare(strict_types = 1);

namespace src\controller;

use const \ROOT;
use \app\App;

class Complaint{

    private $files = [];
    private $errors = [];
    private $user_id ;
    private $model ;

    public function __construct(){
        $this->user_id = App::get_id();
        $this->model = new \src\model\Complaint;
    }
    
    public function get_all($id){

        
        $query = "SELECT complaint.user_id, complaint.status,complaint.upload_date, complaint.id, complaint.to_id,complaint.detail,complaint.subject,users.firstname,users.lastname FROM complaint INNER JOIN users ON complaint.to_id = users.id WHERE complaint.user_id =:id";
        $db = App::get_db();
        $result  =$db->read($query,["id"=>$id]);
        
       
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function make_complaint():bool{

        $success = false;

         if($this->check_for_file() === false){

                $success = $this->model->add();

         }else{
            if($this->upload_files()){
                $success = $this->model->add($this->files);
                //if the addition to database was not successfull
                if($success === false){
                    foreach($this->files as $file){
                        //delete already uploaded files from the system
                        unlink($file);
                    }
                }
            }
           
         }


        return $success;
    }

    public function get_error_message(){

        return JSON_encode($this->error);
    }

    public function upload_files():bool{
        

        $upload_status = true;

        $allowed_types = ["jpg","jpeg","png","mp3","mp4","doc","pdf"];
        $target_dir = ROOT."/assets/uploads/";
        $num_of_files_to_upload = count($_FILES["files"]["name"]);
        $num_of_uploaded = 0;

       for($i = 0; $i < $num_of_files_to_upload; $i++){ 

            $uploadOk = true;
            $hash_name = md5((string)$this->user_id).basename($_FILES["files"]["name"][$i]); 
            $target_file = $target_dir.$hash_name;
            $image_file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Check if file already exists
            if (file_exists($target_file)) {
                $this->error[] = "Sorry, file already exists.";
                array_push($this->files,$target_file);
                $uploadOk = false;
            }

            if(!in_array($image_file_type,$allowed_types)){
                
                $this->error[] =  "Sorry,only certain types allowed.";
                $uploadOk = false;
                $upload_status = false;
            }

            if ($uploadOk) {
            // if everything is ok, try to upload file
                 //keep track of files that are about to be uploaded

                 if (move_uploaded_file($_FILES["files"]["tmp_name"][$i], $target_file)) {
                     ++$num_of_uploaded;
                     array_push($this->files,$target_file);
                 } else {
                     $this->error[] = "Sorry, there was an error uploading your file.";
                 }
            }     
    }

    if(($num_of_files_to_upload === $num_of_uploaded) || $upload_status===true){
        return true;
    }else{
        return false;
    }
}

private function check_for_file():bool{

    
    if($_FILES["files"]["name"][0] !== ""){

        return true;
    }

    return false;
}

public function update(){

    if($_SERVER["REQUEST_METHOD"]=="POST"){

        $param = file_get_contents("php://input");
        $param = JSON_decode($param,true);
        $result = true;

        switch($param["key"]){
            case "status":
            $result = $this->model->update_status($param["id"],$param["value"]);
            break;
            case "to_id":
                $result = $this->model->update_to_id($param["id"],$param["to_id"]);
                break;
            default:
            die("an error occured");
        }

        if($result){

            return JSON_encode([
                "status"=>true
            ]);
        }else{
            return JSON_encode([
                "status"=>false
            ]);
        }
    }
}

public function get_files(){

    if($_SERVER["REQUEST_METHOD"]=="GET" && isset($_GET["complaint_id"])){

        $this->files = $this->model->get_files($_GET["complaint_id"]);

        if(empty($this->files)){

            return JSON_encode([]);
        }else{

            $all_files = []; 

            foreach($this->files as $file){
                $length = strlen($file["location"]);
                array_push($all_files,["location"=>substr($file["location"],20,$length-1),"id"=>$file["id"],"type"=>strtolower(pathinfo($file["location"],PATHINFO_EXTENSION))]);
            }
            return JSON_encode([
                "status"=>true,
                "files"=>$all_files
            ]);
        }

    }

    http_response_code(404);
    echo JSON_encode("Route not found");
}

public function delete_files(){

    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["complaint_id"])){

        $files = $this->model->get_files($_GET["complaint_id"]);

        foreach($files as $file){
        
            if(file_exists($file["location"])){
                unlink($file["location"]);
            }
        }
        return JSON_encode([
            "status"=>true
        ]);

    }else{

        header("HTTP/1.1 400 bad request");
    }
}


public function delete_file(){

    $response["status"] = false;

    if($_SERVER["REQUEST_METHOD"] == "DELETE" ){

        $data = JSON_decode(file_get_contents("php://input"));
        $result = $this->model->delete_file($data->file_id);
        
        if($result){
            $response["status"] = true;
        }
    }else{

        header("HTTP/1.1 400 bad request");
    }

    return JSON_encode($response);
}

}