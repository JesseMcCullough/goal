<?php

// This script is always a request.
include_once("../constants.php");
include_once(CLASS_PATH . "Goal.php");

$goalId = $_GET["goalId"];

$goal = new Goal($goalId);
$goal->deleteGoal();
