<?php

include_once("../classes/Category.php");

if (!isset($_SESSION)) {
    session_start();
}

$category = new Category(null);
$category->createCategory($_GET["categoryName"], $_GET["categoryHexColor"], $_SESSION["user_id"]);

?>
