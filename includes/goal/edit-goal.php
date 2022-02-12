<?php

/*
 * Edits a goal.
 * A goal ID is required. $_POST["goalId"].
 * A goal's name is optional. $_POST["name"].
 * A goal's categoryId is optional. $_POST["categoryId"].
 * A goal's steps are optional. $_POST["steps"].
 * 
 * If the user does not own the category, goal, or step, "unverified" will be returned.
 * If the goal no longer has any steps, the goal will be deleted and null will be returned.
 * In any other case, the goal ID will be returned.
 */

// This script is always a request.
include_once("../constants.php");
include_once(CLASS_PATH . "Goal.php");
include_once(CLASS_PATH . "Category.php");

if (!isset($_SESSION)) {
    session_start();
}

$goalId = $_POST["goalId"];

$name = null;
if (isset($_POST["name"])) {
    $name = $_POST["name"];
}

$categoryId = null;
if (isset($_POST["categoryId"])) {
    $categoryId = $_POST["categoryId"];
    $category = new Category($categoryId);

    if (!$category->verifyCategoryOwnership($_SESSION["user_id"])) {
        echo "unverified";
        exit();
    }
}

$goal = new Goal($goalId);

if (!$goal->verifyGoalOwnership($_SESSION["user_id"])) {
    echo "unverified";
    exit();
}

$editedName = false;
if ($name != null && !$goal->isSameName($name)) {
    $editedName = true;
}

$editedCategoryId = false;
if ($categoryId != null && !$goal->isSameCategory($categoryId)) {
    $editedCategoryId = true;
}

$goal->editGoal($name, $categoryId);

if (isset($_POST["steps"])) {
    $steps = json_decode($_POST["steps"]);
    for ($x = 0; $x < count($steps); $x++) {
        // [$x][0] = name, [$x][1] = date, [$x][2] = optional id
        $step = $steps[$x];

        if (count($step) == 3) {
            $name = $step[0];
            $date = $step[1];

            if (!$goal->verifyStepOwnership($step[2])) {
                echo "unverified";
                exit();
            }

            if ($name == null && $date == null) {
                $goal->deleteStep($step[2]);
            } else {
                $goal->editStep($step[0], $step[1], $step[2]);
            }
        } else {
            $goal->addStep($step[0], $step[1]);
        }
    }
}

if ($goal->getStepsTotal() == 0) {
    $goal->deleteGoal();
    $goalId = null;
}

$goalDetails = array("goalId" => $goalId, "editedName" => $editedName, "editedCategory" => $editedCategoryId);

echo json_encode($goalDetails);
