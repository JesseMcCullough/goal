<?php

require_once("../classes/Database.php");

class Category {

    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function createCategory($name, $hexColor) {
        $query = "SELECT name FROM categories WHERE name = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("s", $name);
        $statement->execute();

        $result = $statement->get_result();

        // If the category exists
        if ($result->num_rows > 0) {
            echo "Category exists";
            return false;
        }
        // Category does not exist. Create category.

        $query = "INSERT INTO categories (name, hex_color) VALUES (?, ?)";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ss", $name, $hexColor);
        $statement->execute();

        return true;
    }
    
}
