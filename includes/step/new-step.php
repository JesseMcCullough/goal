<?php

/*
 * Views a goal's new step to be inserted with data.
 * A step counter is optional but recommended. $_POST["step"].
 * A step hex color is optional but recommened. $_POST["hexColor"].
 */
 
$step = 1; // step counter.
if (isset($_POST["step"])) {
    $step = htmlspecialchars($_POST["step"]);
}

$hexColor = "#ECECEC";
if (isset($_POST["hexColor"])) {
    $hexColor = htmlspecialchars($_POST["hexColor"]);
}

?>

<div class="goal step step-<?php echo $step; ?>" style="border-color: <?php echo $hexColor; ?>"
    <?php if (isset($_POST["id"])) { ?>data-step-id="<?php echo $_POST["id"]; }?>">
    <input type="text" class="step" name="step-<?php echo $step; ?>" placeholder="I need to ..."
    <?php if (isset($_POST["stepName"])) { ?>value="<?php echo $_POST["stepName"]; }?>" />
    <input type="date" class="date" name="step-<?php echo $step; ?>-date"
    value="<?php if (isset($_POST["date"])) { echo $_POST["date"] . "\" style=\"color: #393939"; } else { echo "2022-02-02"; }?>" />
</div>
