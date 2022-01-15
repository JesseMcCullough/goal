<?php
 
if (!isset($_GET["step"])) {
    $step = 1;
} else {
    $step = htmlspecialchars($_GET["step"]);
}

$hexColor = "#ECECEC";

if (isset($_GET["hexColor"])) {
    $hexColor = $_GET["hexColor"];
}

?>

<!-- may or may not make this a form. to be determined. -->
<div class="goal step" style="border-color: <?php echo $hexColor; ?>">
    <input type="text" class="step" name="step-<?php echo $step; ?>" placeholder="I need to ..." />
    <input type="text" class="date" name="step-<?php echo $step; ?>-date" placeholder="January 11, 2022" />
</div>
