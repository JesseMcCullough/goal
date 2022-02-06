<?php

require_once(CLASS_PATH . "Database.php");

class UserSignUp {

    private $firstName;
    private $lastName;
    private $email;
    private $password;
    private $database;

    public function __construct($firstName, $lastName, $email, $password) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
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

        $query = "INSERT INTO users (first_name, last_name, email, hashed_password) VALUES (?, ?, ?, ?)";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ssss", $this->firstName, $this->lastName, $this->email, $hashed_password);
        $statement->execute();

        session_start();
        $id = $statement->insert_id;
        $_SESSION["user_id"] = $id;

        return true;
    }
    

}