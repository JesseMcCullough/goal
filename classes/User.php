<?php

require_once(CLASS_PATH . "Database.php");

/**
 * A user, identified by an ID and has a first name, last name, email, and password.
 * 
 * To signup or login a user, create a new User object with a null ID and call the
 * respective method. If the signup or login was successful, the User object
 * will be supplied with that user's ID.
 */
class User {

    private $id;
    private $database;

    /**
     * Creates a user.
     * 
     * @param id User's ID. Set to null for signup/login.
     */
    public function __construct($id) {
        $this->id = $id;
        $this->database = new Database();
    }

    /**
     * Gets the user's first name.
     * 
     * @return User's first name.
     */
    public function getFirstName() {
        $query = "SELECT first_name FROM users WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();

        $result = $statement->get_result();
        return $result->fetch_assoc()["first_name"];
    }

    /**
     * Gets the user's last name.
     * 
     * @return User's last name.
     */
    public function getLastName() {
        $query = "SELECT last_name FROM users WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();

        $result = $statement->get_result();
        return $result->fetch_assoc()["last_name"];
    }

    /**
     * Gets the user's email.
     * 
     * @return User's email.
     */
    public function getEmail() {
        $query = "SELECT email FROM users WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();

        $result = $statement->get_result();
        return $result->fetch_assoc()["email"];
    }

    /**
     * Signs up the user.
     * 
     * @param firstName User's first name
     * @param lastName User's last name
     * @param email User's email
     * @param password User's password, unhashed
     * @return false if the user exists; otherwise true if the signup was successful.
     */
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

        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION["user_id"] = $this->id;

        return true;
    }

    /**
     * Logs in the user.
     * 
     * @param email User's email
     * @param password User's password, unhashed
     * @return false if the user does not exist or if password is invalid;
     *          otherwise true if the login was successful.
     */
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

        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION["user_id"] = $this->id;

        return true;
    }

}
