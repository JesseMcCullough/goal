<?php

// This script is always a request.
include_once("../constants.php");
include_once(CLASS_PATH . "Goal.php");

if (!isset($_SESSION)) {
    session_start();
}

$goalId = $_GET["goalId"];

$goal = new Goal($goalId);

if (!$goal->verifyGoalOwnership($_SESSION["user_id"])) {
    echo "unverified";
    exit();
}

$goal->deleteGoal();
