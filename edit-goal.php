<?php include_once("includes/header.php"); ?>

<h1>Let's plan this out.</h1>

<div class="goal edit-goal" style="border-color: #ECECEC">
    <input type="text" class="name" value="<?php echo $_POST["goal"]; ?>" />
</div>

<div class="categories-container">
    <?php include_once("includes/categories.php"); ?>
</div>

<div class="divider"></div>

<h2>Next Steps</h2> 
<a href="#" class="add-step">Add Step</a>
<div class="steps">
    <?php include("includes/new-step.php"); ?>
</div>

<div class="buttons">
    <button type="button" class="done">Done</button>
</div>

<script src="javascript/edit-goal.js"></script>

<?php include_once("includes/footer.php"); ?>
