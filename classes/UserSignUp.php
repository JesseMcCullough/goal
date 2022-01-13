<?php

require_once("classes/Database.php");

class UserSignUp {

    private $email;
    private $password;
    private $database;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
        $this->database = new Database();
    }

    public function signUp() {
        $query = "SELECT id FROM users WHERE email = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("s", $this->email);
        $statement->execute();

        $result = $statement->get_result();

        // If the user already exists.
        if ($result->num_rows > 0) {
            return false;
        }
        // User does not exist. Sign up.

        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (email, hashed_password) VALUES (?, ?)";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ss", $this->email, $hashed_password);
        $statement->execute();

        return true;
    }
    

}