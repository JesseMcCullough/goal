<?php

session_start();
if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include_once("includes/header.php");

?>

<h1>Login</h1>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" autocomplete="off" class="login">
    <input type="email" name="email" placeholder="Email" autocomplete="off" />
    <input type="password" name="password" placeholder="Password" autocomplete="off" />
    <div class="buttons">
        <button type="submit" name="submit">Login</button>
        <a href="signup.php">Sign Up</a>
    </div>
</form>

<?php

if (isset($_POST["submit"])) {
    if (!isset($_POST["email"])) {
        echo "Email not set";
        return;
    }

    if (!isset($_POST["password"])) {
        echo "Password not set";
        return;
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    include(CLASS_PATH . "User.php");

    $user = new User(null);
    if ($user->login($email, $password)) {
        header("Location: index.php");
    } else {
        echo "Failed login";
    }

}

?>

<?php include_once(INCLUDE_PATH . "footer.php"); ?>
