<?php

if (!isset($_GET["goalId"])) {
    header("Location: index.php");
    exit();
}

ob_start();

$_POST["title"] = "changeTitle";
$_POST["isAuthorizationRequired"] = true;
include_once("includes/header.php");

?>

<h1>You can accomplish anything.</h1>

<?php

$_POST["goalId"] = $_GET["goalId"];
include_once(INCLUDE_PATH . "goal/view-goal.php");

$contents = str_replace("changeTitle", $goal->getName(), ob_get_contents());
ob_clean();
echo $contents;

ob_end_flush();

$_POST["newCategory"] = $category->getName();
include(INCLUDE_PATH . "category/categories.php");

?>

<div class="divider"></div>

<h2>Next Steps</h2> 
<div class="steps">
    <?php

    foreach ($goal->getSteps() as $step) {
        $_POST["id"] = $step["id"];
        $_POST["name"] = $step["name"];
        $_POST["date"] = $step["dateFormatted"];
        $_POST["hexColor"] = $category->getHexColor();
        $_POST["isCompleted"] = $step["isCompleted"];
        include(INCLUDE_PATH . "step/view-step.php");
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

<?php

addJavaScript("step-checkbox");
addJavaScript("view-goal-close");

include_once(INCLUDE_PATH . "footer.php");

?>
