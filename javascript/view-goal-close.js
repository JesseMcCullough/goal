let goalId = document.querySelector(".goal").dataset.goalId;
let closeButton = document.querySelector(".close");
closeButton.addEventListener("click", onClickCloseButton);

function onClickCloseButton() {
    if (isCategoryChangedWhileViewingGoal) {
         // Send request to edit goal's categeory.
        let requestUrl = "includes/goal/edit-goal.php";
        let params = "goalId=" + goalId + "&categoryId=" + categoryId;

        let editGoalRequest = new XMLHttpRequest();
        editGoalRequest.open("POST", requestUrl, true);
        editGoalRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        editGoalRequest.send(params);
    }

    location.href = "index.php";
}
