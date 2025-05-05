<?php
    class Database{
        private $host = "localhost:3306";
        private $user = "test";
        private $password = "test";
        private $database = "fctproject";
        
        public function getConnection(){
            $hostDB = "mysql:host=".$this->host.";dbname=".$this->database.";";

            try{
                $connection = new PDO($hostDB, $this->user, $this->password);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $connection;
            }catch(PDOException $e){
                die("Error: " . $e->getMessage());
            }
        }
    }
?>