<?php

ob_start();

$_POST["title"] = "changeTitle";
$_POST["isAuthorizationRequired"] = true;
include_once("includes/header.php");

$title = null;
$goalName = null;
$goal = null;
if (isset($_POST["goalId"])) {
    $goal = new Goal($_POST["goalId"]);

    if (!$goal->verifyGoalOwnership($_SESSION["user_id"])) {
        header("Location: index.php");
        exit();
    }

    $goalName = $goal->getName();
    $title = "Edit Goal";

    addNotification("Editing Goal: <strong>" . $goalName . "</strong>");
} else {
    $goalName = $_POST["goal"];
    $title = "New Goal";

    addNotification("Creating a new goal");
}

if (!empty($_POST["categoryIdPreselect"])) {
    $category = new Category($_POST["categoryIdPreselect"]);

    if (!$category->verifyCategoryOwnership($_SESSION["user_id"])) {
        header("Location: index.php");
        exit();
    }
}

$contents = str_replace("changeTitle", $title, ob_get_contents());
ob_clean();
echo $contents;

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
            if (!$goal->verifyStepOwnership($step["id"])) {
                header("Location: index.php");
                exit();
            }

            $_POST["step"] = $stepCounter++;
            $_POST["id"] = $step["id"];
            $_POST["stepName"] = $step["name"];
            $_POST["date"] = $step["date"];
            $_POST["hexColor"] = $goal->getCategory()->getHexColor();
            include(INCLUDE_PATH . "step/new-step.php");
        }
    } else {
        include(INCLUDE_PATH . "step/new-step.php");
    }

    ob_end_flush();
    
    ?>
</div>

<div class="buttons">
    <button type="button" class="done">Done</button>
</div>

<?php

addJavaScript("edit-goal");

include_once(INCLUDE_PATH . "footer.php");

?>
