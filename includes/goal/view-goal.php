<?php

/*
 * Views a goal.
 * A goal ID must be provided, so set $_POST["goalId"] to the ID or have a Goal object
 *      available within this scope.
 * 
 * IF the goal does not belong to the user, the user will be redirected to the dashboard.
 */

$goalId = -1;
$goalName = null;
if (isset($_POST["goalId"])) {
    $goalId = $_POST["goalId"];
    $goalName = $_POST["goalName"];
    $goal = new Goal($goalId);
} else {
    // Goal object available in scope.
    $goalId = $goal->getId();
    $goalName = $goal->getName();
}

if (!$goal->verifyGoalOwnership($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

$category = $goal->getCategory();
$goalUrl = urlencode(str_replace(" ", "-", $goalName)) . "-" . $goalId;

?>

<a href="<?php echo $goalUrl; ?>" class="goal-link">
    <div class="goal<?php if (isset($_POST["goalId"])) { echo " view-goal"; } else { echo " click"; } ?>" style="border-color: <?php echo $category->getHexColor(); ?>"
        data-goal-id="<?php echo $goalId; ?>"
        data-category-id="<?php echo $category->getId(); ?>"
        data-category-hex-color="<?php echo $category->getHexColor(); ?>">
        <span class="name"><?php echo $goal->getName(); ?></span>
        <span class="date"><img src="images/clock-icon.png" /><?php echo $goal->getDateFormatted(); ?></span>
        <div class="progress">
            <?php $progressPercentage = $goal->getProgressPercentage(); ?>
            <div class="completion-bar" style="width: <?php echo $progressPercentage; ?>"></div>
            <span class="percent"><?php echo $progressPercentage; ?></span>
        </div>
    </div>
</a>
