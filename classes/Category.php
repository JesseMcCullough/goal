<?php

require_once(CLASS_PATH . "Database.php");

/**
 * A goal's category, identified by an ID and has a name, a hex color, and a user ID.
 * 
 * To create a new category, create a new Category object and set its ID to null
 * and call its respective method.
 */
class Category {

    private $id;
    private $database;

    /**
     * Creates a new Category. For a new category, set id to null.
     */
    public function __construct($id) {
        $this->id = $id;
        $this->database = new Database();
    }

    /**
     * Gets the category's ID.
     * 
     * @return Category's ID.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the category's name.
     * 
     * @return Category's name.
     */
    public function getName() {
        $query = "SELECT name FROM categories WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
        
        $result = $statement->get_result();
        return $result->fetch_assoc()["name"];
    }

    /**
     * Gets the category's hex color.
     * 
     * @return Category's hex color.
     */
    public function getHexColor() {
        $query = "SELECT hex_color FROM categories WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
        
        $result = $statement->get_result();
        return $result->fetch_assoc()["hex_color"];
    }

    /**
     * Creates a new category.
     * 
     * @param name Category's name
     * @param hexColor Category's hex color
     * @param userId Category's user ID
     * @return false if the category already exists; otherwise, true if the
     *          category was successfully created.
     */
    public function createCategory($name, $hexColor, $userId) {
        $query = "SELECT id, name FROM categories WHERE name = ? AND user_id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("si", $name, $userId);
        $statement->execute();

        $result = $statement->get_result();

        // If the category exists
        if ($result->num_rows > 0) {
            $this->id = $result->fetch_assoc()["id"];
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

    /**
     * Edits a category.
     * 
     * @param name Category's name, optional
     * @param hexColor Category's hex color, optional
     */
    public function editCategory($name, $hexColor) {
        $query = "UPDATE categories SET ";
        if (isset($name) && isset($hexColor)) {
            $query .= "name = ?, hex_color = ? ";
        } else if (isset($name)) {
            $query .= "name = ? " ;
        } else if (isset($hexColor)) {
            $query .= "hex_color = ? ";
        }
        $query .= "WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        if (isset($name) && isset($hexColor)) {
            $statement->bind_param("ssi", $name, $hexColor, $this->id);
        } else if (isset($name)) {
            $statement->bind_param("si", $name, $this->id);
        } else if (isset($hexColor)) {
            $statement->bind_param("si", $hexColor, $this->id);
        }
        $statement->execute();
    }

    /**
     * Deletes a category.
     */
    public function deleteCategory() {
        $query = "DELETE FROM categories WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
    }

    /**
     * Gets the categories of a user.
     * 
     * @param userId User's ID
     */
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

    /**
     * Verifies category ownership by a user.
     * 
     * @param userId User's ID
     * @return true if the category belongs to the user; otherwise, false.
     */
    public function verifyCategoryOwnership($userId) {
        if ($this->id == -1) { // default category, implicitly belongs to all users.
            return true;
        }

        $query = "SELECT id, user_id FROM categories WHERE id = ? AND user_id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ii", $this->id, $userId);
        $statement->execute();

        $result = $statement->get_result();

        // If the category does not belong to the user.
        if ($result->num_rows <= 0) {
            return false;
        }
        // Category belongs to the user.

        return true;
    }
    
}
