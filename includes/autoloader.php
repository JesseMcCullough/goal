<?php

/*
 * Automatically loads classes without the need to include them beforehand.
 */

require_once("constants.php");

spl_autoload_register("autoloader");

function autoloader($className) {
    include_once(CLASS_PATH . "$className.php");
}
