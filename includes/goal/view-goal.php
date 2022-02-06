<?php

$goalId = -1;
if (isset($_GET["goalId"])) {
    $goalId = $_GET["goalId"];
    $goal = new Goal($goalId);
} else {
    // Goal object available in scope.
    $goalId = $goal->getId();
}

$category = $goal->getCategory();

?>

<div class="goal<?php if (isset($_GET["goalId"])) { echo " view-goal"; } else { echo " click"; } ?>" style="border-color: <?php echo $category->getHexColor(); ?>"
    data-goal-id="<?php echo $goalId; ?>"
    data-category-id="<?php echo $category->getId(); ?>"
    data-category-hex-color="#<?php echo $category->getHexColor(); ?>">
    <span class="name"><?php echo $goal->getName(); ?></span>
    <span class="date"><img src="images/clock-icon.png" /><?php echo $goal->getDateFormatted(); ?></span>
    <div class="progress">
        <?php $progressPercentage = $goal->getProgressPercentage(); ?>
        <div class="completion-bar" style="width: <?php echo $progressPercentage; ?>"></div>
        <span class="percent"><?php echo $progressPercentage; ?></span>
    </div>
</div>
