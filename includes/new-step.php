<?php
 
if (!isset($_GET["step"])) {
    $step = 1;
} else {
    $step = htmlspecialchars($_GET["step"]);
}

?>

<!-- may or may not make this a form. to be determined. -->
<div class="goal step" style="border-color: #84D87E">
    <input type="text" class="step" name="step-<?php echo $step; ?>" placeholder="I need to ..." />
    <input type="text" class="date" name="step-<?php echo $step; ?>-date" placeholder="January 11, 2022" />
</div>
