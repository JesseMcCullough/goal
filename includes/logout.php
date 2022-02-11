<?php

/*
 * Logs out the user, regardless of whether the user was previously logged in.
 */

session_start();

$_SESSION = array();

session_destroy();

header("Location: ../login.php");
exit();

?>
