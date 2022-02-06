<?php

require_once(CLASS_PATH . "Database.php");

class UserLogin {

    private $email;
    private $password;
    private $database;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
        $this->database = new Database();
    }

    public function login() {
        $query = "SELECT id, email, hashed_password FROM users WHERE email = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("s", $this->email);
        $statement->execute();

        $result = $statement->get_result();

        // If the user does not exist.
        if ($result->num_rows <= 0) {
            return false;
        }
        // User does exist. Try to login.

        $user = $result->fetch_assoc();
        $hashed_password = $user["hashed_password"];

        // If the password is invalid.
        if (!password_verify($this->password, $hashed_password)) {
            return false;
        }
        // Password is valid.

        session_start();
        $_SESSION["user_id"] = $user["id"];

        return true;
    }
    

}