<?php

/*
 * Creates a new category.
 * A category name is required. $_POST["categoryName"].
 * A category hex color is required. $_POST["categoryHexColor"].
 * 
 * The new category's ID will be returned.
 */

// This script is always a request.
include_once("../constants.php");
include_once(CLASS_PATH . "Category.php");

if (!isset($_SESSION)) {
    session_start();
}

$category = new Category(null);
$category->createCategory($_POST["categoryName"], $_POST["categoryHexColor"], $_SESSION["user_id"]);

echo $category->getId();

?>
