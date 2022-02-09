<?php

/*
 * Edit's a category.
 * A category ID is required. $_POST["categoryID"].
 * A category name is optional but recommended. $_POST["categoryName"].
 * A category hex color is optional but recommended. $_POST["categoryHexColor"].
 * 
 * If the category does not belong to the user, "unverified" will be returned.
 * If the category name is not set, the category will be deleted and -1 will be returned.
 * In any other case, the category ID will be returned.
 */

// This script is always a request.
include_once("../constants.php");
include_once(CLASS_PATH . "Category.php");

if (!isset($_SESSION)) {
    session_start();
}

$categoryId = $_POST["categoryId"];

$category = new Category($categoryId);

if (!$category->verifyCategoryOwnership($_SESSION["user_id"])) {
    echo "unverified";
    exit();
}

$name = null;
if (isset($_POST["categoryName"])) {
    $name = $_POST["categoryName"];
}

$hexColor = null;
if (isset($_POST["categoryHexColor"])) {
    $hexColor = $_POST["categoryHexColor"];
}

$category->editCategory($name, $hexColor);

if ($name == null) {
    $category->deleteCategory();

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
