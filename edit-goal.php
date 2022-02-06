<?php

include_once("includes/header.php");

$goalName = null;
$goal = null;
if (isset($_POST["goalId"])) {
    $goal = new Goal($_POST["goalId"]);
    $goalName = $goal->getName();
} else {
    $goalName = $_POST["goal"];
}

?>

<h1>Let's plan this out.</h1>

<div class="goal edit-goal" style="border-color: #ECECEC">
    <input type="text" name="goal" class="name" value="<?php echo $goalName; ?>"
    <?php if (isset($_POST["goalId"])) { ?>data-goal-id="<?php echo $_POST["goalId"]; } ?>" />
    <?php if (!empty($_POST["categoryIdPreselect"])) : ?>
        <input type="hidden" name="categoryIdPreselect" value="<?php echo $_POST["categoryIdPreselect"]; ?>" />
    <?php endif; ?>
</div>

<?php include(INCLUDE_PATH . "category/categories.php"); ?>

<div class="divider"></div>

<h2>Next Steps</h2> 
<a href="#" class="add-step">Add Step</a>
<div class="steps">
    <?php
    
    if (isset($_POST["steps"])) {
        $steps = json_decode($_POST["steps"], true);
        $stepCounter = 1;
        foreach ($steps as $step) {
            $_GET["step"] = $stepCounter++;
            $_GET["id"] = $step["id"];
            $_GET["stepName"] = $step["name"];
            $_GET["date"] = $step["date"];
            $_GET["hexColor"] = $goal->getCategory()->getHexColor();
            include(INCLUDE_PATH . "step/new-step.php");
        }
    } else {
        include(INCLUDE_PATH . "step/new-step.php");
    }
    
    ?>
</div>

<div class="buttons">
    <button type="button" class="done">Done</button>
</div>

<?php

addJavaScript("edit-goal");

include_once(INCLUDE_PATH . "footer.php");

?>
