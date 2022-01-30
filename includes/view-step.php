<?php

$name = $_GET["name"];
$date = $_GET["date"];
$hexColor = $_GET["hexColor"];

?>

<div class="goal step view" style="border-color: #<?php echo $hexColor; ?>">
    <span class="name"><?php echo $name; ?></span>
    <span class="date"><img src="images/clock-icon.png" /><?php echo $date; ?></span>
    <div class="container">
        <div class="checkbox"></div>
    </div>
</div>
