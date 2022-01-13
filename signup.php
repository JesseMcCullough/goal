<?php include_once("includes/header.php"); ?>

<h1>Sign Up</h1>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" autocomplete="off">
    <input type="email" name="email" placeholder="Email" autocomplete="off" />
    <input type="password" name="password" placeholder="Password" autocomplete="off" />
    <button type="submit" name="submit">Sign Up</button>
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

    include("classes/UserSignUp.php");

    $signUp = new UserSignUp($email, $password);
    if ($signUp->signUp()) {
        echo "Successfully registered";
    } else {
        echo "Failed";
    }

}

?>

<?php include_once("includes/footer.php"); ?>