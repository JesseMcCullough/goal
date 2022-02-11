<?php

require_once(CLASS_PATH . "Database.php");

/**
 * A goal, identified by an ID and has a name, date (of the latest date of its steps),
 * category ID, user ID, and steps.
 * 
 * To create a new goal, create a new Goal object with the ID of null
 * and call the respective method.
 */
class Goal {

    private $id;
    private $database;
    
    /**
     * Creates a goal.
     * 
     * @param id Goal's ID. Set to null for a new goal.
     */
    public function __construct($id) {
        $this->id = $id;
        $this->database = new Database();
    }

    /**
     * Gets the goal's ID.
     * 
     * @return Goal's ID.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the goal's name.
     * 
     * @return Goal's name.
     */
    public function getName() {
        $query = "SELECT name FROM goals WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
        
        $result = $statement->get_result();
        return $result->fetch_assoc()["name"];
    }


    /**
     * Gets the goal's date, which is the latest date of its steps.
     * 
     * @return null if the goal has no steps; otherwise, returns the latest
     *          date of its steps.
     */
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

    /**
     * Gets the goal's formatted date, as determined by Goal#formatDate.
     * 
     * @return Goal's formatted date.
     */
    public function getDateFormatted() {
        return $this->formatDate($this->getDate());
    }

    /**
     * Gets the goal's category ID.
     * 
     * @return Goal's category ID.
     */
    public function getCategoryId() {
        $query = "SELECT category_id FROM goals WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
        
        $result = $statement->get_result();
        return $result->fetch_assoc()["category_id"];
    }

    /**
     * Gets the goal's category as a Category object.
     * 
     * @return Goal's category as a Category object.
     */
    public function getCategory() {
        include_once(CLASS_PATH . "Category.php");
        return new Category($this->getCategoryId());
    }

    /**
     * Creates a goal.
     * 
     * @param name Goal's name
     * @param categoryId Goal's category ID
     * @param userId Goal's user ID
     */
    public function createGoal($name, $categoryId, $userId) {
        // duplicate goals allowed and are up to the user's discretion.
        $query = "INSERT INTO goals (user_id, category_id, name) VALUES (?, ?, ?)";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("iis", $userId, $categoryId, $name);
        $statement->execute();

        $this->id = $statement->insert_id;
    }

    /**
     * Edits a goal
     * 
     * @param name Goal's new name, optional
     * @param categoryId Goal's new category ID, optional
     */
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

    /**
     * Deletes a goal.
     */
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

    /**
     * Adds a step to the goal.
     * 
     * @param name Step's name
     * @param date Step's date
     */
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

    /**
     * Edits a goal's step.
     * 
     * @param name Step's name
     * @param date Step's date
     * @param stepId Step's ID
     */
    public function editStep($name, $date, $stepId) {
        $query = "UPDATE goal_steps SET name = ?, step_date = ? WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ssi", $name, $date, $stepId);
        $statement->execute();
    }

    /**
     * Deletes a step.
     * 
     * @param stepId Step's ID.
     */
    public function deleteStep($stepId) {
        // Check if step was completed.
        if ($this->getStepIsCompleted($stepId)) {
            // Update goal steps completed.
            $newStepsCompleted = $this->getStepsCompleted() - 1;
            $query = "UPDATE goals SET steps_completed = ? WHERE id = ?";

            $statement = $this->database->getConnection()->prepare($query);
            $statement->bind_param("ii", $newStepsCompleted, $this->id);
            $statement->execute();
        }

        // Update goal steps count.
        $newStepsTotal = $this->getStepsTotal() - 1;
        $query = "UPDATE goals SET steps_total = ? WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ii", $newStepsTotal, $this->id);
        $statement->execute();

        // Delete step.
        $query = "DELETE FROM goal_steps WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $stepId);
        $statement->execute();
    }

    // TO-DO: Create a Step class for more conventional method names.
    /**
     * Gets a step's completion status.
     * 
     * @param stepId Step's ID
     * @return true if completed; otherwise, false.
     */
    public function getStepIsCompleted($stepId) {
        $query = "SELECT is_completed FROM goal_steps WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $stepId);
        $statement->execute();

        $result = $statement->get_result();
        return boolval($result->fetch_assoc()["is_completed"]);
    }

    /**
     * Sets a step's completion status.
     * 
     * @param isCompleted true/false
     * @param stepId Step's ID
     */
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

    /**
     * Gets the goal's steps.
     * 
     * @return Goal's steps.
     */
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

    /**
     * Gets the goal's total steps.
     * 
     * @return Goal's total steps.
     */
    public function getStepsTotal() {
        $query = "SELECT steps_total FROM goals WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
        
        $result = $statement->get_result();
        return $result->fetch_assoc()["steps_total"];
    }

    /**
     * Gets the goal's amount of completed steps.
     * 
     * @return Goal's amount of completed steps.
     */
    public function getStepsCompleted() {
        $query = "SELECT steps_completed FROM goals WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();
        
        $result = $statement->get_result();
        return $result->fetch_assoc()["steps_completed"];
    }

    /**
     * Gets the goal's progress percentage.
     * 
     * @return Goal's progress percentage.
     */
    public function getProgressPercentage() {
        if ($this->getStepsTotal() == 0) {
            return "0%";
        }

        return ceil($this->getStepsCompleted() / $this->getStepsTotal() * 100) . "%";
    }

    /**
     * Gets the goals of a user.
     * 
     * @param userId User's ID.
     */
    public static function getGoals($userId) {
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

    /**
     * Verifies goal ownership by a user.
     * 
     * @param userId User's ID
     * @return true if the goal belongs to the user; otherwise, false.
     */
    public function verifyGoalOwnership($userId) {
        $query = "SELECT id FROM goals WHERE id = ? AND user_id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ii", $this->id, $userId);
        $statement->execute();

        $result = $statement->get_result();

        // If the goal does not belong to the user.
        if ($result->num_rows <= 0) {
            return false;
        }
        // Goal does belong to the user.

        return true;
    }

    /**
     * Verifies step ownership by a goal.
     * 
     * @param stepId Step's ID
     * @return true if the step belongs to the goal; otherwise, false.
     */
    public function verifyStepOwnership($stepId) {
        $query = "SELECT id FROM goal_steps WHERE id = ? AND goal_id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("ii", $stepId, $this->id);
        $statement->execute();

        $result = $statement->get_result();

        // If the step does not belong to the goal.
        if ($result->num_rows <= 0) {
            return false;
        }
        // Step does belong to the goal.

        return true;
    }

    public function isSameName($name) {
        $query = "SELECT name FROM goals WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();

        $goal = $statement->get_result()->fetch_assoc();
        return $goal["name"] == $name;
    }

    public function isSameCategory($categoryId) {
        $query = "SELECT category_id FROM goals WHERE id = ?";

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();

        $goal = $statement->get_result()->fetch_assoc();
        return $goal["category_id"] == $categoryId;
    }

    /**
     * Formats a date.
     * Example format: January 1, 2022
     * 
     * @return Formatted date.
     */
    private function formatDate($date) {
        $dateObj = date_create($date);
        return date_format($dateObj, "F j, Y");
    }

}
