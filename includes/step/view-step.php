<?php

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
