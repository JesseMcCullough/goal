let steps = document.querySelectorAll(".goal.step");
for (let step of steps) {
    step.addEventListener("click", function() {
        let checkbox = step.querySelector(".checkbox");
        checkbox.classList.toggle("checked");

        let isCompleted = checkbox.classList.contains("checked");

        let goalRequest = new XMLHttpRequest();
        let requestUrl = "includes/step/edit-step-completion.php?goalId=" + goalId
                + "&stepId=" + step.dataset.stepId
                + "&isCompleted=" + isCompleted;
                
        goalRequest.open("GET", requestUrl, true);
        goalRequest.onload = function() {
            if (this.status == 200) {
                let progressPercentage = this.responseText;
                document.querySelector(".progress .completion-bar").style.width = progressPercentage;
                document.querySelector(".progress .percent").innerHTML = progressPercentage;
            }
        };
        goalRequest.send();
    });
}