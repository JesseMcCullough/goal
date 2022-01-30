let step = 1;
let steps = document.querySelector(".steps");
let addStep = document.querySelector(".add-step");
addStep.addEventListener("click", onClickAddStep);

let done = document.querySelector(".done");
done.addEventListener("click", onClickDone);

/**
 * Adds a new step input when the add step button is clicked.
 */
function onClickAddStep() {
    step++;

    // Request a new step and insert it at the beginning.
    let newStepRequest = new XMLHttpRequest();
    newStepRequest.open("GET", "includes/new-step.php?step=" + step + "&hexColor=" + hexColor, true);
    newStepRequest.onload = function() {
        if (this.status == 200) {
            steps.insertAdjacentHTML("afterbegin", this.responseText);
        }
    };
    newStepRequest.send();
}

function onClickDone() {
     // get goal name
    let goal = document.querySelector(".goal input[name='goal']").value;
    console.log(goal);

    let isValidGoal = true;
    let steps = [];

    for (let x = 1; x <= step; x++) {
        let isValidStep = true;

        let stepName = document.querySelector("input[name='step-" + x);
        let stepDate = document.querySelector("input[name='step-" + x + "-date");

        let hasName = stepName.value !== "";
        let hasDate = stepDate.value !== "";
        if (hasName || hasDate) {
            if (!hasName) {
                stepName.style.borderColor = "#FF0000";
                isValidGoal = false;
                isValidStep = false;
                console.log("Doesn't have a name but has a date");
                console.log(stepDate.value);
            } else {
                stepName.style.borderColor = "#ECECEC";
            }
            
            if (!hasDate) {
                stepDate.style.borderColor = "#FF0000";
                isValidGoal = false;
                isValidStep = false;
                console.log("Doesn't have a date but has a name");
                console.log(stepName.value);
            } else {
                stepDate.style.borderColor = "#ECECEC";
            }

            if (isValidStep) {
                // valid entry.
                console.log("Putting in " + stepName.value + " and " + stepDate.value);
                steps.push([stepName.value, stepDate.value]);
            }
        }
    }

    if (isValidGoal) {
        // Request a new goal to be created.
        let requestUrl = "includes/new-goal.php?categoryId=" + categoryId
        + "&goal=" + encodeURIComponent(goal)
        + "&steps=" + JSON.stringify(steps);

        let newGoalRequest = new XMLHttpRequest();
        newGoalRequest.open("GET", requestUrl, true);
        newGoalRequest.onloadend = function() {
            if (this.status == 200) {
                let goalId = this.responseText;
                location.href = "view-goal.php?goalId=" + goalId;
            }
        };
        newGoalRequest.send();
    }

}