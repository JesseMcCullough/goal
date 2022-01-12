<?php include_once("includes/header.php"); ?>

<span id="greeting">Hi, Jesse</span>
<h1>What do you want to accomplish?</h1>
<form action="edit-goal.php" method="POST">
    <input type="text" name="goal" placeholder="I want to ..." />
    <button type="submit">Go</button>
</form>

<div class="divider"></div>

<ul class="categories">
    <li><div class="color-square" style="background-color: #F6EE00"></div>Education</li>
    <li><div class="color-square" style="background-color: #84D87E"></div>Finance</li>
    <li><div class="color-square" style="background-color: #8000FF"></div>Business</li>
    <li class="new-category">New Category</li>
</ul>
<span class="sort-by">Sort By</span>

<div class="goal" style="border-color: #F6EE00">
    <span class="name">Earn my bachelor's degree in computer science</span>
    <span class="date"><img src="images/clock-icon.png" />May 1, 2024</span>
    <div class="progress">
        <div class="completion-bar" style="width: 50%"></div>
        <span class="percent">50%</span>
    </div>
</div>

<div class="goal" style="border-color: #8000FF">
    <span class="name">Generate 10 new customers</span>
    <span class="date"><img src="images/clock-icon.png" />February 1, 2022</span>
    <div class="progress">
        <div class="completion-bar" style="width: 90%"></div>
        <span class="percent">90%</span>
    </div>
</div>

<div class="goal" style="border-color: #84D87E">
    <span class="name">Save $2,000</span>
    <span class="date"><img src="images/clock-icon.png" />June 1, 2022</span>
    <div class="progress">
        <div class="completion-bar" style="width: 5%"></div>
        <span class="percent">5%</span>
    </div>
</div>

<?php include_once("includes/footer.php"); ?>
