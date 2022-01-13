<?php include_once("includes/header.php"); ?>

<h1>Login</h1>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" autocomplete="off">
    <input type="email" name="email" placeholder="Email" autocomplete="off" />
    <input type="password" name="password" placeholder="Password" autocomplete="off" />
    <button type="submit" name="submit">Login</button>
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

    include("classes/UserLogin.php");

    $login = new UserLogin($email, $password);
    if ($login->login()) {
        echo "Successfully logged in";
        
        header("Location: index.php");
    } else {
        echo "Failed login";
    }

}

?>

<?php include_once("includes/footer.php"); ?>