let step = document.querySelectorAll(".steps .goal.step").length;
let steps = document.querySelector(".steps");
let addStep = document.querySelector(".add-step");
addStep.addEventListener("click", onClickAddStep);

let done = document.querySelector(".done");
done.addEventListener("click", onClickDone);

applyCategoryIdPreselect();
applyDateInputEvents();

/**
 * Adds a new step input when the add step button is clicked.
 */
function onClickAddStep() {
    step++;

    // Request a new step and insert it at the beginning.
    let requestUrl = "includes/step/new-step.php";
    let params = "step=" + step + "&hexColor=" + encodeURIComponent(hexColor);

    let newStepRequest = new XMLHttpRequest();
    newStepRequest.open("POST", requestUrl, true);
    newStepRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    newStepRequest.onload = function() {
        if (this.status == 200) {
            steps.insertAdjacentHTML("afterbegin", this.responseText);
            applyDateInputEvents();
        }
    };
    newStepRequest.send(params);
}

function onClickDone() {
     // get goal name
    let goal = document.querySelector(".goal input[name='goal']");
    let goalName = goal.value.trim();

    let isValidGoal = true;
    let steps = [];

    for (let x = 1; x <= step; x++) {
        let isValidStep = true;

        let step = document.querySelector(".goal.step-" + x);
        let stepName = document.querySelector("input[name='step-" + x);
        let stepDate = document.querySelector("input[name='step-" + x + "-date");

        let hasName = stepName.value !== "";
        let hasDate = stepDate.value !== "";
        let hasId = step.hasAttribute("data-step-id");
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
                if (hasId) {
                    steps.push([stepName.value, stepDate.value, step.dataset.stepId]);
                } else {
                    steps.push([stepName.value, stepDate.value]);
                }
            }
        } else {
            if (hasId) {
                steps.push([null, null, step.dataset.stepId]);
            }
        }
    }

    if (isValidGoal) {
        let requestUrl = null;
        let params = null;

        let isExistingGoal = goal.hasAttribute("data-goal-id");
        if (isExistingGoal) {
            let goalId = goal.dataset.goalId;
            if (goalName != "") {
                // Request an existing goal to be updated.
                requestUrl = "includes/goal/edit-goal.php";
                params = "goalId=" + goalId
                        + "&name=" + encodeURIComponent(goalName)
                        + "&categoryId=" + categoryId
                        + "&steps=" + JSON.stringify(steps);
            } else {
                // Request an existing goal to be deleted.
                requestUrl = "includes/goal/delete-goal.php";
                params = "goalId=" + goalId;
            }
        } else {
            // Request a new goal to be created.
            requestUrl = "includes/goal/new-goal.php";
            params = "categoryId=" + categoryId
                    + "&goal=" + encodeURIComponent(goalName)
                    + "&steps=" + JSON.stringify(steps);
        }

        let goalRequest = new XMLHttpRequest();
        goalRequest.open("POST", requestUrl, true);
        goalRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        goalRequest.onloadend = function() {
            if (this.status == 200) {
                let goalId = this.responseText;
                if (goalId != "unverified") {
                    location.href = "view-goal.php?goalId=" + goalId;  
                } else {
                    location.href = "index.php";
                }
            }
        };
        goalRequest.send(params);
    }

}

function applyCategoryIdPreselect() {
    let categoryIdInput = document.querySelector("input[name='categoryIdPreselect']");
    if (categoryIdInput) {
        let categoryId = categoryIdInput.value;
        let category = document.querySelector(".category[data-category-id='" + categoryId + "']");
        setActiveCategory(category);
    }
}

function applyDateInputEvents() {
    let dateInputs = document.querySelectorAll(".date");
    for (let dateInput of dateInputs) {
        dateInput.addEventListener("input", function() {
            dateInput.style.color = "#393939";
        });

        dateInput.addEventListener("click", function() {
            dateInput.style.color = "#393939";
        });
    }
}
