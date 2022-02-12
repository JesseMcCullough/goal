<?php

ob_start();

$_POST["title"] = "Sign Up";
include_once("includes/header.php");

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

?>

<h1>Sign Up</h1>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" autocomplete="off" class="signup">
    <input type="text" name="firstName" placeholder="First name" autocomplete="off" <?php if (isset($_POST["firstName"])) { echo 'value="' . $_POST["firstName"] . '"'; } ?>/>
    <input type="text" name="lastName" placeholder="Last name" autocomplete="off" <?php if (isset($_POST["lastName"])) { echo 'value="' . $_POST["lastName"] . '"'; } ?>/>
    <input type="email" name="email" placeholder="Email" autocomplete="off" <?php if (isset($_POST["email"])) { echo 'value="' . $_POST["email"] . '"'; } ?>/>
    <input type="password" name="password" placeholder="Password" autocomplete="off" <?php if (isset($_POST["password"])) { echo 'value="' . $_POST["password"] . '"'; } ?>/>
    <div class="buttons">
        <button type="submit" name="submit">Sign Up</button>
        <a href="login.php">Login</a>
    </div>
</form>

<?php

if (isset($_POST["submit"])) {
    $canAttemptSignUp = false;
    $notification = "Please provide your ";
    $missingFields = [];

    if (empty(trim($_POST["firstName"]))) {
        $missingFields[] = "first name";
    }

    if (empty(trim($_POST["lastName"]))) {
        $missingFields[] = "last name";
    }

    if (empty(trim($_POST["email"]))) {
        $missingFields[] = "email";
    }

    if (empty($_POST["password"])) {
        $missingFields[] = "password";
    }
    
    if (!empty($missingFields)) {
        $fields = "";
        $missingFieldsCount = count($missingFields);
        $requiresCommaSeparation = $missingFieldsCount >= 3;

        if ($missingFieldsCount == 1) {
            $fields = $missingFields[0];
        } else {
            for ($x = 0; $x < $missingFieldsCount; $x++) {
                if ($requiresCommaSeparation) {
                    if ($x < $missingFieldsCount - 1) { // Any element that's not the last element.
                        $fields .= $missingFields[$x] . ", ";
                    } else if ($x == $missingFieldsCount - 1) { // The element is the element.
                        $fields .= "and " . $missingFields[$x];
                    }
                } else { // Only two missing fields.
                    if ($x == 0) {
                        $fields .= $missingFields[$x] . " and ";
                    } else {
                        $fields .= $missingFields[$x];
                    }
                }
            }
        }

        $notification .= $fields;
    } else {
        $canAttemptSignUp = true;
        $notification = null;
    }

    addNotification($notification, null, "error");

    if ($canAttemptSignUp) {
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        $user = new User(null);
        if ($user->signUp($firstName, $lastName, $email, $password)) {
            header("Location: index.php?signUp=true");
            exit();
        } else {
            addNotification("An account already exists with that email. <a href='login.php'>Login</a>", null, "error");
        }
    }
}

ob_end_flush();

?>

<?php include_once(INCLUDE_PATH . "footer.php"); ?>
