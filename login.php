<?php

ob_start();

$_POST["title"] = "Login";
$_POST["isCategoriesShown"] = false;
include_once("includes/header.php");

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

?>

<h1>Login</h1>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" autocomplete="off" class="login">
    <input type="email" name="email" placeholder="Email" autocomplete="off" <?php if (isset($_POST["email"])) { echo 'value="' . $_POST["email"] . '"'; } ?>/>
    <input type="password" name="password" placeholder="Password" autocomplete="off" <?php if (isset($_POST["password"])) { echo 'value="' . $_POST["password"] . '"'; } ?>/>
    <div class="buttons">
        <button type="submit" name="submit">Login</button>
        <a href="signup.php">Sign Up</a>
    </div>
</form>

<?php
if (isset($_POST["submit"])) {
    $canAttemptLogin = false;
    $notification = "Please provide your ";
    if (empty(trim($_POST["email"]))) {
        $notification .= "email";

        if (empty($_POST["password"])) {
            $notification .= " and password";
        }
    } else if (empty($_POST["password"])) {
        $notification .= "password";
    } else {
        $notification = null;
        $canAttemptLogin = true;
    }

    addNotification($notification, null, "error");

    if ($canAttemptLogin) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        include(CLASS_PATH . "User.php");

        $user = new User(null);
        if ($user->login($email, $password)) {
            header("Location: index.php");
            exit();
        } else {
            addNotification("Invalid email/password", null, "error");
        }
    }
}

ob_end_flush();

?>

<?php include_once(INCLUDE_PATH . "footer.php"); ?>
