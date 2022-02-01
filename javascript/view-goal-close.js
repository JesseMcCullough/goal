let goalId = document.querySelector(".goal").dataset.goalId;
let closeButton = document.querySelector(".close");
closeButton.addEventListener("click", onClickCloseButton);

function onClickCloseButton() {
    if (isCategoryChangedWhileViewingGoal) {
         // Send request to edit goal's categeory.
        let requestUrl = "includes/edit-goal.php?goalId=" + goalId + "&categoryId=" + categoryId
        let editGoalRequest = new XMLHttpRequest();
        editGoalRequest.open("GET", requestUrl, true);
        editGoalRequest.send();
    }

    location.href = "index.php";
}
