<?php

include_once("../classes/Category.php");

$category = new Category($_GET["categoryName"], $_GET["categoryHexColor"]);
$category->createCategory();

?>
