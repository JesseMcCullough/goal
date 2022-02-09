<?php

class Database {

    private $name;
    private $host;
    private $username;
    private $password;
    private $connection;

    public function __construct() {
        $configString = file_get_contents(SERVER_PATH . "config.json");
        $database = json_decode($configString, true)["database"];

        $this->name = $database["name"];
        $this->host = $database["host"];
        $this->username = $database["username"];
        $this->password = $database["password"];
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->name);
    }

    public function getConnection() {
        return $this->connection;
    }

}
