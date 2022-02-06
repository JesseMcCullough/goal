<?php

// This script is always a request.
include_once("../constants.php");
include_once(CLASS_PATH . "Category.php");

if (!isset($_SESSION)) {
    session_start();
}

$categoryId = $_GET["categoryId"];

$category = new Category($categoryId);

if (!$category->verifyCategoryOwnership($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

$name = null;
if (isset($_GET["categoryName"])) {
    $name = $_GET["categoryName"];
}

$hexColor = null;
if (isset($_GET["categoryHexColor"])) {
    $hexColor = $_GET["categoryHexColor"];
}

$category->editCategory($name, $hexColor);

if ($name == null) {
    $category->deleteCategory();

    if (!isset($_SESSION)) {
        session_start();
    }

    include_once(CLASS_PATH . "Goal.php");

    foreach (Goal::getGoals($_SESSION["user_id"]) as $goal) {
        if ($goal->getCategoryId() == $categoryId) {
            $goal->editGoal(null, -1);
        }
    }

    echo -1;
} else {
    echo $category->getId();
}
