<?php

include_once("../../classes/Goal.php");

$goalId = $_GET["goalId"];
$isCompleted = $_GET["isCompleted"];
$stepId = $_GET["stepId"];

$goal = new Goal($goalId);
$goal->setStepIsCompleted($isCompleted, $stepId);

echo $goal->getProgressPercentage();
