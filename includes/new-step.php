<?php
 
$step = 1; // step counter.
if (isset($_GET["step"])) {
    $step = htmlspecialchars($_GET["step"]);
}

$hexColor = "#ECECEC";
if (isset($_GET["hexColor"])) {
    $hexColor = htmlspecialchars($_GET["hexColor"]);
}

?>

<div class="goal step step-<?php echo $step; ?>" style="border-color: <?php echo $hexColor; ?>"
    <?php if (isset($_GET["id"])) { ?>data-step-id="<?php echo $_GET["id"]; }?>">
    <input type="text" class="step" name="step-<?php echo $step; ?>" placeholder="I need to ..."
    <?php if (isset($_GET["stepName"])) { ?>value="<?php echo $_GET["stepName"]; }?>" />
    <input type="date" class="date" name="step-<?php echo $step; ?>-date"
    value="<?php if (isset($_GET["date"])) { echo $_GET["date"] . "\" style=\"color: #393939"; } else { echo "2022-02-02"; }?>" />
</div>
