<?php

$_POST["isAuthorizationRequired"] = true;
include_once("includes/header.php");

$user = new User($_SESSION["user_id"]);

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

addJavaScript("sort-goals");
addJavaScript("new-goal-category-preselect");

include_once(INCLUDE_PATH . "footer.php");

?>
