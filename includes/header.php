<?php

/*
 * Generates a page's <head> and starter <body> tags.
 * To set a page's title, set $_POST["title"] to the title.
 * To not generate the new categories popup, set $_POST["isCategoriesShown"] to false.
 * To add a JavaScript file, call addJavaScript("file-name") without the .js extension.
 */

include_once("autoloader.php");

if (!isset($_SESSION)) {  
    session_start();
}

// If isAuthorizationRequired is set to true, a user must be logged in to view the page.
$isAuthorizationRequired = false;
if (isset($_POST["isAuthorizationRequired"])) {
    $isAuthorizationRequired = $_POST["isAuthorizationRequired"];
}

if ($isAuthorizationRequired && !isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// If isCategoriesShown is set to false, the new categories popup will not be generated.
$isCategoriesShown = true;
if (isset($_POST["isCategoriesShown"])) {
    $isCategoriesShown = $_POST["isCategoriesShown"];
}

// JavaScript files. These are added within footer.php.
$scripts = [];
function addJavaScript($path) {
    global $scripts;
    $scripts[] = $path;
}

addJavaScript("notifications");
addJavaScript("select-category");
addJavaScript("new-category");

// Events. These are added within footer.php.
$events = [];
function addEventToDataLayer($event) {
    global $events;
    $events[] = $event;
}

// If a title is given, the title will be "GIVEN_TITLE | Goaler".
$title = "Goaler";
if (isset($_POST["title"])) {
    $temp = $title;
    $title = $_POST["title"] . " | " . $temp;
}

// Notifications.
$notifications = [];
function addNotification($text, $duration = null, $type = null) {
    global $notifications;
    if ($text) {
        $notifications[] = ["text" => $text, "duration" => $duration, "type" => $type];
    }
}

?>

<!DOCTYPE html>
<html>
<head> 
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="style.css" />

    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-M37SWSJ');</script>

    <title><?php echo $title; ?></title>
</head>
<body>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M37SWSJ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <div class="notifications"></div>

    <?php if ($isCategoriesShown) : ?>
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
    <?php endif; ?>

    <div class="container">
