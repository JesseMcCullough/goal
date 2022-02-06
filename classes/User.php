<?php

require_once(CLASS_PATH . "Database.php");

class User {

    private $id;
    private $database;

    public function __construct($id) {
        $this->id = $id;
        $this->database = new Database();
    }

    public function getFirstName() {
        $query = "SELECT first_name FROM users WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();

        $result = $statement->get_result();
        return $result->fetch_assoc()["first_name"];
    }

    public function getLastName() {
        $query = "SELECT last_name FROM users WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();

        $result = $statement->get_result();
        return $result->fetch_assoc()["last_name"];
    }

    public function getEmail() {
        $query = "SELECT email FROM users WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();

        $result = $statement->get_result();
        return $result->fetch_assoc()["email"];
    }

    public function signUp($firstName, $lastName, $email, $password) {
        $query = "SELECT id FROM users WHERE email = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("s", $email);
        $statement->execute();

        $result = $statement->get_result();

        // If the user already exists.
        if ($result->num_rows > 0) {
            return false;
        }
        // User does not exist. Sign up.

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (first_name, last_name, email, hashed_password) VALUES (?, ?, ?, ?)";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ssss", $firstName, $lastName, $email, $hashed_password);
        $statement->execute();

        $this->id = $statement->insert_id;

        session_start();
        $_SESSION["user_id"] = $this->id;

        return true;
    }

    public function login($email, $password) {
        $query = "SELECT id, email, hashed_password FROM users WHERE email = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("s", $email);
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
        if (!password_verify($password, $hashed_password)) {
            return false;
        }
        // Password is valid.

        $this->id = $user["id"];

        session_start();
        $_SESSION["user_id"] = $this->id;

        return true;
    }

}
