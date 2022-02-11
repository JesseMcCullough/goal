<?php

/*
 * Defines constants, primarily of correct paths.
 * SERVER_PATH returns the path to the server.
 * CLASS_PATH returns the path to the classes folder.
 * INCLUDE_PATh returns the path to the includes folder.
 */

$path = $_SERVER["DOCUMENT_ROOT"];
if (strpos($path, "Development")) {
    $path .= "/Goaler/git";
}
$path .= "/";

define("SERVER_PATH", $path);
define("CLASS_PATH", $path . "classes/");
define("INCLUDE_PATH", $path . "includes/");
