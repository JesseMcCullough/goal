<?php

include_once("../classes/Goal.php");

$goalId = $_GET["goalId"];

$name = null;
if (isset($_GET["name"])) {
    $name = $_GET["name"];
}

$categoryId = null;
if (isset($_GET["categoryId"])) {
    $categoryId = $_GET["categoryId"];
}

$goal = new Goal($goalId);
$goal->editGoal($name, $categoryId);

// add support for editing steps.

echo $goalId;
