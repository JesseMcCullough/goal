<?php

include_once("../../classes/Goal.php");

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
if (isset($_GET["steps"])) {
    $steps = json_decode($_GET["steps"]);
    for ($x = 0; $x < count($steps); $x++) {
        // [$x][0] = name, [$x][1] = date, [$x][2] = optional id
        $step = $steps[$x];

        if (count($step) == 3) {
            $name = $step[0];
            $date = $step[1];

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
