<?php

$_POST["isAuthorizationRequired"] = true;
include_once("includes/header.php");

$user = new User($_SESSION["user_id"]);

if (isset($_GET["editedCategory"])) {
    addNotification("Swiftly edited that goal's category after closing it", 5000);
}

?>

<span id="greeting">Hi, <?php echo $user->getFirstName(); ?></span>
<a href="includes/logout.php" id="logout">Logout</a>
<h1>What do you want to accomplish?</h1>
<form action="edit-goal.php" method="POST" class="goal">
    <input type="text" name="goal" placeholder="I want to ..." />
    <input type="hidden" name="categoryIdPreselect" />
    <button type="submit" class="go">Go</button>
</form>

<?php include(INCLUDE_PATH . "category/categories.php"); ?>

<div class="divider"></div>

<div class="goals">
    <?php
    
    $goals = Goal::getGoals($_SESSION["user_id"]);
    foreach ($goals as $goal) {
        include(INCLUDE_PATH . "goal/view-goal.php");
    }

    ?>
</div>

<?php

if (isset($_GET["signUp"]) && filter_var($_GET["signUp"], FILTER_VALIDATE_BOOLEAN)) {
    addNotification("Thanks for signing up. Get started by creating your first goal.");
}

addJavaScript("sort-goals");
addJavaScript("new-goal-category-preselect");

include_once(INCLUDE_PATH . "footer.php");

?>
