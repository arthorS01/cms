<?php

namespace src\model;
use \app\App;
class Complaint{

    public function add(array $files=null){

        if(!isset($_POST["anonymous"])){
            $_POST["anonymous"] = 0;
        }else{
            $_POST["anonymous"] = 1;
        }

        $status = null;

        if($files !== null){
            
           $status =  $this->add_complaints_with_file($files);
           
        }else{

            $status = $this->add_complaints_without_file();
        }

        return $status;
    }

    private function add_complaints_with_file(array $files){


        $db = App::get_db()->get_connection();
            $db->beginTransaction();

       
            $stmt = $db->prepare("INSERT INTO complaint(user_id,subject,detail,anonymous,to_id) VALUES(:user_id, :subject, :detail,:anonymous,:to_id)");
            $stmt->execute([
                ":user_id"=>App::get_id("id"),
                ":subject"=>$_POST["subject"],
                ":detail"=>$_POST["detail"],
                ":to_id"=>$_POST["to_id"],
                ":anonymous"=>$_POST["anonymous"]
            ]);

            $complaint_id = $db->lastInsertId();

            
            foreach($files as $index=>$file){

                $file_type = strtolower(pathinfo($file,PATHINFO_EXTENSION));
                $query = "INSERT INTO complaint_files(complaint_id,file_type,location) VALUES(:complaint_id,:file_type,:location)";
                
                $stmt2 = $db->prepare($query); 
                $stmt2->execute([
                    ":complaint_id"=>$complaint_id,
                    ":file_type"=>$file_type,
                    ":location"=>$file
                ]);
            }

           $result =  $db->commit();

           if($result){
            return true;
           }

           return false;
        
     }

     
    private function add_complaints_without_file():bool{

        $db = App::get_db()->get_connection();

        
            $stmt = $db->prepare("INSERT INTO complaint(user_id,subject,detail,to_id) VALUES(:user_id, :subject, :detail,:to_id)");
            $result = $stmt->execute([
                ":user_id"=>App::get_id("id"),
                ":subject"=>$_POST["subject"],
                ":detail"=>$_POST["detail"],
                ":to_id"=>$_POST["to_id"]
            ]);

            if($result){
                return true;
            }else{
                return false;
            }

    }
    public function update_status($id,$status){

        $query = "Update complaint SET status = :status WHERE id = :id";
        $db = \app\App::get_db();
        $result = $db->read($query,["status"=>$status,"id"=>$id]);

        return (bool)$result;
    }

    public function get(){

    }

    public function get_files($id){

        $query = "SELECT * FROM complaint_files WHERE complaint_id = :id";
        $db = \app\App::get_db();
        $result = $db->read($query,["id"=>$id]);

        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete_file($id){


       $query = "SELECT location from complaint_files WHERE id = :id";
       $db  = \app\App::get_db();

       $result = $db->read($query,["id"=>$id]);
       $result= $result->fetchAll(\PDO::FETCH_ASSOC);

      $location = $result[0]["location"];
       
       if(file_exists($location)){
            $query = "DELETE FROM complaint_files WHERE id= :id";
            $result = $db->delete($query,["id"=>$id]);

            if($result){
                unlink($location);
                return true;
            }
       }

       return false;
    }
    }
