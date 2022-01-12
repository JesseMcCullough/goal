<?php include_once("includes/header.php"); ?>

<h1>You can accomplish anything.</h1>

<div class="goal" style="border-color: #F6EE00">
    <span class="name">Earn my bachelor's in computer science</span>
    <span class="date"><img src="images/clock-icon.png" />May 1, 2024</span>
    <div class="progress">
        <div class="completion-bar" style="width: 50%"></div>
        <span class="percent">50%</span>
    </div>
</div>

<ul class="categories">
    <li><div class="color-square" style="background-color: #F6EE00"></div><span class="active">Education</span></li>
    <li><div class="color-square" style="background-color: #84D87E"></div>Finance</li>
    <li><div class="color-square" style="background-color: #8000FF"></div>Business</li>
    <li class="new-category">New Category</li>
</ul>

<div class="divider"></div>

<h2>Next Steps</h2> 
<a href="#" class="add-step">Add Step</a>
<div class="steps">

    <?php include("includes/view-step.php"); ?>
</div>

<div class="buttons">
    <button type="button" class="close">Close</button>
</div>

<?php include_once("includes/footer.php"); ?>