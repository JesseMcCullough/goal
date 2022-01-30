<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/goals/git/classes/Database.php");

class Goal {

    private $id;
    private $database;
    
    // need to redesign this class to view goal and also create goal.
    public function __construct($id) {
        $this->id = $id;
        $this->database = new Database();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        $query = "SELECT name FROM goals WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
        
        $result = $statement->get_result();
        return $result->fetch_assoc()["name"];
    }

    public function getDate() {
        return "January 28, 2022"; // return latest goal step date.
    }

    public function getCategoryId() {
        $query = "SELECT category_id FROM goals WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
        
        $result = $statement->get_result();
        return $result->fetch_assoc()["category_id"];
    }

    public function getCategory() {
        include_once($_SERVER["DOCUMENT_ROOT"] . "/goals/git/classes/Category.php");
        return new Category($this->getCategoryId());
    }

    public function createGoal($name, $categoryId, $userId) {
        // duplicate goals allowed and are up to the user's discretion.
        $query = "INSERT INTO goals (user_id, category_id, name) VALUES (?, ?, ?)";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("iis", $userId, $categoryId, $name);
        $statement->execute();

        $this->id = $statement->insert_id;
    }

    public function editGoal($name, $categoryId) {
        $query = "UPDATE goals SET ";
        if (isset($name)) {
            $query .= "name = ? ";
        }

        if (isset($categoryId)) {
            $query .= "category_id = ? ";
        }

        $query .= "WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        if (isset($name) && isset($categoryId)) {
            $statement->bind_param("sii", $name, $categoryId, $this->id);
        } else if (isset($name)) {
            $statement->bind_param("si", $name, $this->id);
        } else if (isset($categoryId)) {
            $statement->bind_param("ii", $categoryId, $this->id);
        }
        $statement->execute();
    }

    public function addStep($name, $date) {
        $query = "INSERT INTO goal_steps (goal_id, name, step_date, is_completed) VALUES (?, ?, ?, FALSE)";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("iss", $this->id, $name, $date);
        $statement->execute();

        // Update goal steps count.
        $newStepsTotal = $this->getStepsTotal() + 1;
        $query = "UPDATE goals SET steps_total = ? WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ii", $newStepsTotal, $this->id);
        $statement->execute();
    }

    public function getSteps() {
        $steps = [];
        $query = "SELECT name, step_date, is_completed FROM goal_steps WHERE goal_id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();

        $result = $statement->get_result();
        while ($step = $result->fetch_assoc()) {
            $steps[] =
                [
                    "name" => $step["name"],
                    "date" => $step["step_date"],
                    "isCompleted" => $step["is_completed"]
                ];
        }

        return $steps;
    }

    public function getStepsTotal() {
        $query = "SELECT steps_total FROM goals WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
        
        $result = $statement->get_result();
        return $result->fetch_assoc()["steps_total"];
    }

    public function getStepsCompleted() {
        $query = "SELECT steps_completed FROM goals WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
        
        $result = $statement->get_result();
        return $result->fetch_assoc()["steps_completed"];
    }

    public function getProgressPercentage() {
        if ($this->getStepsTotal() == 0) {
            return "0%";
        }

        return ceil($this->getStepsCompleted() / $this->getStepsTotal()) * 100 . "%";
    }

    public function getGoals($userId) {
        $goals = [];
        $database = new Database();

        $query = "SELECT id FROM goals WHERE user_id = ?";

        $statement = $database->getConnection()->prepare($query);
        $statement->bind_param("i", $userId);
        $statement->execute();

        $result = $statement->get_result();
        while ($goal = $result->fetch_assoc()) {
            $goals[] = new Goal($goal["id"]);
        }

        return $goals;
    }

}