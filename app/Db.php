<?php

declare (strict_types = 1);

namespace app;

class Db{

    private $connection;

    public function __construct(){

        $dsn = "mysql:host=localhost;dbname=".DBNAME.";";

        $this->connection = new \PDO($dsn,USER,PASSWORD);
    }

    public function get_connection(){

        return $this->connection;
    }

    public function create(String $query, array $param){

        $stmt = $this->connection->prepare($query);

        $keys = array_keys($param);
        $values = array_values($param);

        
        foreach($keys as $index=>$key){

            if($key === "cpassw"){
                continue;
            }
          
            $stmt->bindValue(":".$key,$values[$index]);
        }

        return $stmt->execute();

    }

    public function read(String $query, array $param=null){

        $stmt = $this->connection->prepare($query);

        if($param !== null){

            $keys = array_keys($param);
            $values = array_values($param);
    
            foreach($keys as $index=>$key){
    
                $stmt->bindValue(":".$key,$values[$index]);
            }
    
        }
       
        $stmt->execute();

        return $stmt;
    }

    public function update($query,$param=null){

        $stmt = $this->connection->prepare($query);
        if($param !== null){

            $keys = array_keys($param);
            $values = array_values($param);
    
            foreach($keys as $index=>$key){
    
                $stmt->bindValue(":".$key,$values[$index]);
            }
    
        }
       
        $stmt->execute();

        return $stmt;
    }

    public function delete( $query,$param){

        
        $stmt = $this->connection->prepare($query);
        if($param !== null){

            $keys = array_keys($param);
            $values = array_values($param);
    
            foreach($keys as $index=>$key){
    
                $stmt->bindValue(":".$key,$values[$index]);
            }
    
        }
       
        $result = $stmt->execute();

        return $result;
    }

    public function execute_transaction($queries,$params=null){

        $db = $this->connection;

        $db->beginTransaction();

        foreach($queries as $index=>$query){

            $stmt = $db->prepare($query);
            if($params == null){
                $stmt->execute();
            }else{
                $stmt->execute($params[$index]);
            }
          
        }

        return $db->commit();
    }

}
