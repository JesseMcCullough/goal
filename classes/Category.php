<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/goals/git/classes/Database.php");

class Category {

    private $id;
    private $database;

    public function __construct($id) {
        $this->id = $id;
        $this->database = new Database();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        $query = "SELECT name FROM categories WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
        
        $result = $statement->get_result();
        return $result->fetch_assoc()["name"];
    }

    public function getHexColor() {
        $query = "SELECT hex_color FROM categories WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
        
        $result = $statement->get_result();
        return $result->fetch_assoc()["hex_color"];
    }

    public function createCategory($name, $hexColor, $userId) {
        $query = "SELECT name FROM categories WHERE name = ? AND user_id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("si", $name, $userId);
        $statement->execute();

        $result = $statement->get_result();

        // If the category exists
        if ($result->num_rows > 0) {
            echo "Category exists";
            return false;
        }
        // Category does not exist. Create category.

        $query = "INSERT INTO categories (name, hex_color, user_id) VALUES (?, ?, ?)";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ssi", $name, $hexColor, $userId);
        $statement->execute();

        $this->id = $statement->insert_id;

        return true;
    }

    public static function getCategories($userId) {
        $categories = [];

        $query = "SELECT id FROM categories WHERE user_id = ?";

        $database = new Database();
        $statement = $database->getConnection()->prepare($query);
        $statement->bind_param("i", $userId);
        $statement->execute();

        $result = $statement->get_result();

        while ($category = $result->fetch_assoc()) {
            $categories[] = new Category($category["id"]);
        }

        return $categories;
    }
    
}