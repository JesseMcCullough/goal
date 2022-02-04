<?php

include_once("../../classes/Goal.php");

$goalId = $_GET["goalId"];

$goal = new Goal($goalId);
$goal->deleteGoal();
