let goals = document.querySelectorAll(".goal");
for (let goal of goals) {
    goal.addEventListener("click", function() {
        onClickToViewGoal(goal);
    });
}

function onClickToViewGoal(goal) {
    let goalId = goal.dataset.goalId;
    location.href = "view-goal.php?goalId=" + goalId;
}
