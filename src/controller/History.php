<?php



namespace src\controller;

require_once "../app/DataChecker.php";
use \app\DataChecker;

class History{

    public function get_history(){

        $query = "SELECT * FROM history Where user_id = :id";
        $data = \app\App::get_db()->read($query,["id"=>\app\App::get_id()]);

        return $data->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function add_history(){

        if($_SERVER["REQUEST_METHOD"] == "POST"){

            $data = json_decode(file_get_contents("php://input"));
            
            $query = "INSERT INTO history(user_id,body) VALUES(:user_id,:activity)";
            $result = \app\App::get_db()->create($query,["user_id"=>$data->id,"activity"=>$data->msg]);
           
            if($result){

                return JSON_encode("success");
            }else{

                return JSON_encode("error");
            }
        }
        
    }

    public function search(){
       
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            
            $search_value = JSON_decode(file_get_contents("php://input"),true);
            $search_value["search_text"] = DataChecker::sanitize($search_value["search_text"]);

            
            $search_tokens = explode(' ', $search_value["search_text"]);
            $history = \app\App::get_history()->get_history();

            $result = [];

            foreach($search_tokens as $token){
                
                $entry = array_filter($history,function($activity) use($token,$result){

                    $activity_body = $activity["body"];
                    
                   
                    if(stripos($activity_body,$token)){

                            return true;
                       
                    }


                });

                foreach($entry as $row){
                    
                    if(!in_array($row,$result)){
                        array_push($result,$row);
                    }
                }

            }


            return JSON_encode(\app\App::get_view()->render("general/search_history",$result,false,false));
        }
    }

    public function delete(){

        $data = file_get_contents("php://input");
        $data = JSON_decode($data,true);
       
        if($_SERVER["REQUEST_METHOD"] == "DELETE" && isset($data["id"])){

            $query = "DELETE FROM history WHERE user_id = :id";

            $result = \app\App::get_db()->delete($query,["id"=>$data["id"]]);
           if($result){

            return JSON_encode([
                "status"=>true
            ]);
           }else{
            return JSON_encode([
                "status"=>false
            ]);
           }
        }else{
            http_response_code("400");
            return JSON_encode([
                "message"=>"please send the rihgt parameters"
            ]);
        }

      
    }
}