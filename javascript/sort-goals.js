addOnClickEventToAllCategories(sortGoalsByCategory);

function sortGoalsByCategory() {
    // Hide all goals that do not have categoryId.
    console.log(categoryId);
    let goals = document.querySelectorAll(".goals .goal");

    for (let goal of goals) {
        if (goal.dataset.categoryId != categoryId && !isCategoryDeselected) {
            goal.classList.add("hidden");
        } else {
            if (goal.classList.contains("hidden")) {
                goal.classList.remove("hidden");
            }
        }
        
        if (isCategoryDeselected) {
            goal.style.borderColor = goal.dataset.categoryHexColor;
        }
    }


    // let goalsRequest = new XMLHttpRequest();
    // goalsRequest.open("GET", "includes/view-goal.php?categoryId=" + categoryId, true);
    // goalsRequest.onload = function() {
    //     // Show goals.
    //     let goals = document.querySelector(".goals");
    //     goals.innerHTML = this.responseText;

    //     // Add event listeners to goals again (TO-DO)
    //     // addOnClickEventToNewCategoryLink();
    //     // addOnClickEventToAllCategories(setActiveCategory);
    // };
    // goalsRequest.send();
}