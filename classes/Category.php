<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/goals/git/classes/Database.php");

class Category {

    private $name;
    private $hexColor;
    private $database;

    public function __construct($name, $hexColor) {
        $this->name = $name;
        $this->hexColor = $hexColor;
        $this->database = new Database();
    }

    public function getName() {
        return $this->name;
    }

    public function getHexColor() {
        return $this->hexColor;
    }

    public function createCategory() {
        $query = "SELECT name FROM categories WHERE name = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("s", $this->name);
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
        $statement->bind_param("ss", $this->name, $this->hexColor);
        $statement->execute();

        return true;
    }

    public static function getCategories() {
        $categories = [];

        $query = "SELECT * FROM categories";

        $database = new Database();
        $result = $database->getConnection()->query($query);

        // If there are categories.
        if ($result->num_rows > 0) {
            while ($category = $result->fetch_assoc()) {
                $categories[] = new Category($category["name"], $category["hex_color"]);
            }
        }

        return $categories;
    }
    
}
