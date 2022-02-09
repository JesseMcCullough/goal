<?php

/*
 * Views a goal's step.
 * A step ID must be provided, so set $_POST["id"] to the ID.
 * A step name must be provided, so set $_POST["name"] to the name.
 * A step date must be provided, so set $_POST["date"] to the date.
 * A step hex color must be provided, so set $_POST["hexColor"] to the hex color.
 * A step completion status must be provided, so set $_POST["isCompleted"] to the
 *      completion status (true/false).
 */

$id = $_POST["id"];
$name = $_POST["name"];
$date = $_POST["date"];
$hexColor = $_POST["hexColor"];
$isCompleted = $_POST["isCompleted"];

?>

<div class="goal step view" style="border-color: <?php echo $hexColor; ?>" data-step-id="<?php echo $id; ?>">
    <span class="name"><?php echo $name; ?></span>
    <span class="date"><img src="images/clock-icon.png" /><?php echo $date; ?></span>
    <div class="container">
        <div class="checkbox<?php if ($isCompleted) { echo " checked"; } ?>"></div>
    </div>
</div>
