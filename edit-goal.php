<?php include_once("includes/header.php"); ?>

<h1>Let's plan this out.</h1>

<div class="goal edit-goal" style="border-color: #ECECEC">
    <span class="name"><?php echo $_POST["goal"]; ?></span>
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

    <?php include("includes/new-step.php"); ?>
</div>

<div class="buttons">
    <button type="button" class="done">Done</button>
</div>

<script>

let step = 1;
let steps = document.querySelector(".steps");
let addStep = document.querySelector(".add-step");
addStep.addEventListener("click", onClickAddStep);

function onClickAddStep() {
    step++;

    let xhr = new XMLHttpRequest();

    xhr.open("GET", "includes/new-step.php?step=" + step, true);
    xhr.onload = function() {
        if (this.status == 200) {
            steps.insertAdjacentHTML("afterbegin", this.responseText);
        }
    }

    xhr.send();
}

</script>

<?php include_once("includes/footer.php"); ?>
