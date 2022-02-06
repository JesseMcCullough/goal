<?php

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

echo $goalId;
