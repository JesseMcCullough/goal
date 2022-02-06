<?php

$path = $_SERVER["DOCUMENT_ROOT"];
if (strpos($path, "Development")) {
    $path .= "/goals/git";
}
$path .= "/";

define("SERVER_PATH", $path);
define("CLASS_PATH", $path . "classes/");
define("INCLUDE_PATH", $path . "includes/");
