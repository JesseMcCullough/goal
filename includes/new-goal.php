<?php

include_once("../classes/Goal.php");

session_start();

$categoryId = $_GET["categoryId"];
$goalName = $_GET["goal"];
$steps = json_decode($_GET["steps"]);

$goal = new Goal(null);
$goal->createGoal($goalName, $categoryId, $_SESSION["user_id"]);

for ($x = 0; $x < count($steps); $x++) {
    // [$x][0] = name, [$x][1] = date.
    $goal->addStep($steps[$x][0], $steps[$x][1]);
}

echo $goal->getId();
