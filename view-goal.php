<?php

include_once("includes/header.php");

$goalId = $_GET["goalId"];
$goal = new Goal($goalId);
$category = $goal->getCategory();

?>

<h1>You can accomplish anything.</h1>

<div class="goal view-goal" style="border-color: #<?php echo $category->getHexColor(); ?>">
    <span class="name"><?php echo $goal->getName(); ?></span>
    <span class="date"><img src="images/clock-icon.png" /><?php echo $goal->getDate(); ?></span>
    <div class="progress">
        <?php $progressPercentage = $goal->getProgressPercentage(); ?>
        <div class="completion-bar" style="width: <?php echo $progressPercentage; ?>"></div>
        <span class="percent"><?php echo $progressPercentage; ?></span>
    </div>
</div>

<?php

$_GET["newCategory"] = $category->getName();
include_once("includes/categories.php");

?>

<div class="divider"></div>

<h2>Next Steps</h2> 
<div class="steps">
    <?php

    foreach ($goal->getSteps() as $step) {
        $_GET["name"] = $step["name"];
        $_GET["date"] = $step["date"];
        $_GET["hexColor"] = $category->getHexColor();
        include("includes/view-step.php");
    }
    
    ?>
</div>

<div class="buttons">
    <button type="button" class="close">Close</button>
</div>

<script src="javascript/edit-category.js"></script>

<?php include_once("includes/footer.php"); ?>