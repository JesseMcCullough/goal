<?php

// This script is always a request.
include_once("../constants.php");
include_once(CLASS_PATH . "Goal.php");

if (!isset($_SESSION)) {
    session_start();
}

$goalId = $_POST["goalId"];
$isCompleted = $_POST["isCompleted"];
$stepId = $_POST["stepId"];

$goal = new Goal($goalId);

if (!$goal->verifyGoalOwnership($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

$goal->setStepIsCompleted($isCompleted, $stepId);

echo $goal->getProgressPercentage();
