<?php

include_once("autoloader.php");

if (!isset($_SESSION)) {  
    session_start();
}

// Authorization
$isAuthorizationRequired = false;
if (isset($_POST["isAuthorizationRequired"])) {
    $isAuthorizationRequired = $_POST["isAuthorizationRequired"];
}

if ($isAuthorizationRequired && !isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Categories
$isCategoriesShown = true;
if (isset($_POST["isCategoriesShown"])) {
    $isCategoriesShown = $_POST["isCategoriesShown"];
}

// JavaScript files
$scripts = [];
function addJavaScript($path) {
    global $scripts;
    $scripts[] = $path;
}

addJavaScript("select-category");
addJavaScript("new-category");

?>

<!DOCTYPE html>
<html>
<head> 
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Home</title>
</head>
<body>
    <div class="new-category">
        <div class="overlay"></div>
        <div class="container">
            <span class="title">Edit Categories</span>
            <input type="color" name="category-hex-color" />
            <input type="text" name="category-name" placeholder="New Category" />
            <button type="button" class="add-category">Add</button>
            <?php 
            
            if ($isCategoriesShown) {
                $_POST["showNewCategory"] = false; // Edit categories link
                include(INCLUDE_PATH . "category/categories.php");
            }

            ?>
        </div>
    </div>

    <div class="container">
