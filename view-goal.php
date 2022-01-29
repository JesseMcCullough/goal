<?php

include_once("includes/header.php");

$goalId = $_GET["goalId"];
$goal = new Goal($goalId);

?>

<h1>You can accomplish anything.</h1>

<div class="goal" style="border-color: #F6EE00">
    <span class="name"><?php echo $goal->getName(); ?></span>
    <span class="date"><img src="images/clock-icon.png" /><?php echo $goal->getDate(); ?></span>
    <div class="progress">
        <?php $progressPercentage = $goal->getProgressPercentage(); ?>
        <div class="completion-bar" style="width: <?php echo $progressPercentage; ?>"></div>
        <span class="percent"><?php echo $progressPercentage; ?></span>
    </div>
</div>

<ul class="categories">
    <li><div class="color-square" style="background-color: #F6EE00"></div><span class="active">Education</span></li>
    <li><div class="color-square" style="background-color: #84D87E"></div>Finance</li>
    <li><div class="color-square" style="background-color: #8000FF"></div>Business</li>
    <li class="new-category">New Category</li>
</ul>

<div class="divider"></div>

<h2>Next Steps</h2> 
<div class="steps">
    <?php include("includes/view-step.php"); ?>
</div>

<div class="buttons">
    <button type="button" class="close">Close</button>
</div>

<?php include_once("includes/footer.php"); ?>