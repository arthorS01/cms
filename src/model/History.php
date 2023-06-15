<?php

namespace src\model;

class History{

    public function get_history(){

        $query = "SELECT * FROM history Where user_id = :id";
        $data = \app\App::get_db()->read($query,["id"=>\app\App::get_id()]);

        return $data->fetchALl(\PDO::FETCH_ASSOC);
    }
}