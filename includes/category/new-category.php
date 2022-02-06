<?php

// This script is always a request.
include_once("../constants.php");
include_once(CLASS_PATH . "Category.php");

if (!isset($_SESSION)) {
    session_start();
}

$category = new Category(null);
$category->createCategory($_GET["categoryName"], $_GET["categoryHexColor"], $_SESSION["user_id"]);

echo $category->getId();

?>