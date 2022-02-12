let goalId = document.querySelector(".goal").dataset.goalId;
let closeButton = document.querySelector(".close");
closeButton.addEventListener("click", onClickCloseButton);

function onClickCloseButton() {
    let directTo = "index.php";

    if (isCategoryChangedWhileViewingGoal) {
         // Send request to edit goal's categeory.
        let requestUrl = "includes/goal/edit-goal.php";
        let params = "goalId=" + goalId + "&categoryId=" + categoryId;

        let editGoalRequest = new XMLHttpRequest();
        editGoalRequest.open("POST", requestUrl, true);
        editGoalRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        editGoalRequest.onloadend = function() {
            if (this.status == 200) {
                let response = JSON.parse(this.responseText);

                if (response["goalId"] != "unverified") {
                    if (response["editedCategory"]) {
                        directTo += "?editedCategory=true";
                    }

                    location.href = directTo;
                }
            } else {
                location.href = directTo; // Send to index.php anyway incase editGoalRequest somehow fails.
            }
        };
        editGoalRequest.send(params);
    } else {
        location.href = directTo;
    }
}
