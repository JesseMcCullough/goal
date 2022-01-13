<?php

include_once("../classes/Category.php");

echo $_GET["categoryName"];
echo $_GET["categoryHexColor"];

$category = new Category();
$category->createCategory($_GET["categoryName"], $_GET["categoryHexColor"]);

?>