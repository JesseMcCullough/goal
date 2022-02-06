<?php

// This script is always a request.
include_once("../constants.php");
include_once(CLASS_PATH . "Goal.php");

$goalId = $_GET["goalId"];
$isCompleted = $_GET["isCompleted"];
$stepId = $_GET["stepId"];

$goal = new Goal($goalId);
$goal->setStepIsCompleted($isCompleted, $stepId);

echo $goal->getProgressPercentage();
