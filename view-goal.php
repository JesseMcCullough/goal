<?php include_once("includes/header.php"); ?>

<h1>You can accomplish anything.</h1>

<?php

$goalId = $_GET["goalId"];
include_once("includes/view-goal.php");

$_GET["newCategory"] = $category->getName();
include_once("includes/categories.php");

?>

<div class="divider"></div>

<h2>Next Steps</h2> 
<div class="steps">
    <?php

    foreach ($goal->getSteps() as $step) {
        $_GET["id"] = $step["id"];
        $_GET["name"] = $step["name"];
        $_GET["date"] = $step["date"];
        $_GET["hexColor"] = $category->getHexColor();
        include("includes/view-step.php");
    }
    
    ?>
</div>

<div class="buttons">
    <button type="button" class="close">Close</button>
    <form action="edit-goal.php" method="POST" class="view-goal-edit">
        <input type="hidden" name="goalId" value="<?php echo $goalId; ?>" />
        <input type="hidden" name="categoryIdPreselect" value="<?php echo $category->getId(); ?>" />
        <input type="hidden" name="steps" value='<?php echo json_encode($goal->getSteps()); ?>' ?>
        <button type="submit" class="edit">Edit</button>
    </form>
</div>

<script src="javascript/select-category.js"></script>
<script src="javascript/new-category.js"></script>
<script src="javascript/view-goal-close.js"></script>

<?php include_once("includes/footer.php"); ?>
