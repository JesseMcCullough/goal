<?php

/*
 * Edits a step's completion status. In order for the completion status to change,
 * the step's goal ID must be provided, along with the step's new completion status
 * and the step's ID.
 * 
 * Goal ID: $_POST["goalId"]
 * Completion status: $_POST["isCompleted"]
 * Step ID: $_POST["stepId"]
 * 
 * If the step ID or goal ID does not belong to the user, "unverified" will be returned;
 * otherwise, the goal's progress percentage will be returned.
 */

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
    echo "unverified";
    exit();
}

if (!$goal->verifyStepOwnership($stepId)) {
    echo "unverified";
    exit();
}

$goal->setStepIsCompleted($isCompleted, $stepId);

echo $goal->getProgressPercentage();
