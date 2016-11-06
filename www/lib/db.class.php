<?php

class DB{
    protected static $instance = null;
    protected $connection;

    public static $connection_counter;
    public static $query_counter;

    protected function __construct($host, $user, $password, $db_name){

        $this->connection = new mysqli($host, $user, $password, $db_name);

        if(mysqli_connect_error()){
            throw new Exception('Could not connect to DB');
        }
        $this->connection->query("SET NAMES 'utf8'");
        $this->connection->query("SET CHARACTER SET 'utf8'");
        $this->connection->query("SET SESSION collation_connection = 'utf8_general_ci'");
    }

    public static function queryCounter(){
        self::$query_counter = self::$query_counter +  1;
        echo self::$query_counter;
    }

    public static function getInstance($host, $user, $password, $db_name){
        if(self::$instance == null)
        {
            self::$instance = new self($host, $user, $password, $db_name);
        }
        return self::$instance;
    }


    public static function connectionCounter()
    {
        self::$connection_counter++;
        echo self::$connection_counter;
    }

    private function __clone(){

    }

    private function __wakeup(){

    }

    public function getLastId(){
        if(!$this->connection){
            return false;
        }

        $id = $this->connection->insert_id;
        return $id;
    }

    public function query($sql){

        if(!$this->connection){
            return false;
        }

        $result = $this->connection->query($sql);

        if(mysqli_error($this->connection)){
            throw new Exception(mysqli_error($this->connection));
        }

        if(is_bool($result)){
            return $result;
        }

        $data = array();
        while($row  = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }
        return $data;
    }

    public function escape($str){
        return mysqli_escape_string($this->connection, $str);
    }
    
    
}