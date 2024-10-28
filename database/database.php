<?php

class DB {
    //Variables responsible for database connection.
    private $db_host = DB_HOST;
    private $db_user = DB_USER;
    private $db_pass = DB_PASS;
    private $db_name = DB_NAME;
    private $DB_Object;

    //Connects (if available) to the project database.
    function DB_connect() {
        $this->DB_Object = new PDO("mysql:host=". $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_pass);
       return $this->DB_Object;
    }

    //Disconnects the object from the project database.
    function DB_disconnect(): void {
        $this->DB_Object = null;
    }

}