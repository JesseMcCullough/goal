addOnClickEventToAllCategories(sortGoalsByCategory);

function sortGoalsByCategory() {
    // Hide all goals that do not have categoryId.
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
    
}