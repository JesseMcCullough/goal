<?php

require_once(CLASS_PATH . "Database.php");

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
        $query = "SELECT step_date FROM goal_steps WHERE goal_id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
        
        $result = $statement->get_result();
        $latestDate = null;
        while ($row = $result->fetch_assoc()) {
            $date = $row["step_date"];
            if ($date > $latestDate || $latestDate == null) {
                $latestDate = $date;
            }
        }
        
        return $latestDate;
    }

    public function getDateFormatted() {
        return $this->formatDate($this->getDate());
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
        include_once(CLASS_PATH . "Category.php");
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
        if (isset($name) && isset($categoryId)) {
            $query .= "name = ?, category_id = ? ";
        } else if (isset($name)) {
            $query .= "name = ? " ;
        } else if (isset($categoryId)) {
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

    public function deleteGoal() {
        $query = "DELETE FROM goals WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();

        $query = "DELETE FROM goal_steps WHERE goal_id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
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

    public function editStep($name, $date, $stepId) {
        $query = "UPDATE goal_steps SET name = ?, step_date = ? WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ssi", $name, $date, $stepId);
        $statement->execute();
    }

    public function deleteStep($stepId) {
        $query = "DELETE FROM goal_steps WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $stepId);
        $statement->execute();

        // Update goal steps count.
        $newStepsTotal = $this->getStepsTotal() - 1;
        $query = "UPDATE goals SET steps_total = ? WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ii", $newStepsTotal, $this->id);
        $statement->execute();
    }

    public function setStepIsCompleted($isCompleted, $stepId) {
        $isCompleted = filter_var($isCompleted, FILTER_VALIDATE_BOOLEAN);
        $intIsCompleted = boolval($isCompleted);

        $query = "UPDATE goal_steps SET is_completed = ? WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ii", $intIsCompleted, $stepId);
        $statement->execute();

        // Update goal steps count.
        $newStepsCompleted = $this->getStepsCompleted();
        if ($isCompleted) {
            $newStepsCompleted++;
        } else {
            $newStepsCompleted--;
        }

        $query = "UPDATE goals SET steps_completed = ? WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ii", $newStepsCompleted, $this->id);
        $statement->execute();
    }

    public function getSteps() {
        $steps = [];
        $query = "SELECT id, name, step_date, is_completed FROM goal_steps WHERE goal_id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();

        $result = $statement->get_result();
        while ($step = $result->fetch_assoc()) {
            $steps[] =
                [
                    "id" => $step["id"],
                    "name" => $step["name"],
                    "date" => $step["step_date"],
                    "dateFormatted" => $this->formatDate($step["step_date"]),
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

        return ceil($this->getStepsCompleted() / $this->getStepsTotal() * 100) . "%";
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

    private function formatDate($date) {
        $dateObj = date_create($date);
        return date_format($dateObj, "F j, Y");
    }

}
