<?php
class Database{
    function __construct(){
        $dns ="mysql:host=localhost;dbname=techaway";
        $user = "root";
        $pwd = "";
        $this->db = new PDO($dns, $user, $pwd);
        $this->db->exec("set names utf8");
    }
    public $db;
    private function prepareQuery($query){
        return $this->db->prepare($query);
    }
    public function editDatabase($query, $content){
        /* ta bort/spara/uppdatera i databasen */
        $preparedQuery = $this->prepareQuery($query);
        $preparedQuery->execute($content);
        return $preparedQuery->fetchAll(PDO::FETCH_ASSOC);
    }
    public function collectFromDatabase($query){
        /* hämta från databasen */
        $preparedQuery = $this->prepareQuery($query);
        $preparedQuery->execute();
        return $preparedQuery->fetchAll(PDO::FETCH_OBJ);
    }
}
