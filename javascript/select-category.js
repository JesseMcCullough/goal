let defaultHexColor = "#ECECEC";
let hexColor = defaultHexColor; // Hex color used for goal and step border color; changes by user input.
let categoryId = -1;
let isCategoryChangedWhileViewingGoal = false;
let isCategoryDeselected = false;

addOnClickEventToAllCategories(setActiveCategory);

/**
 * Adds a click event to all the categories.
 */
function addOnClickEventToAllCategories(event) {
    let categories = document.querySelectorAll(".category");

    for (let category of categories) {
        category.addEventListener("click", function() {
            event(this);
        });
    }
}

/**
 * Sets the active category.
 */
function setActiveCategory(category, ignoreDeselect) {
    let categorySpan = category.querySelector("span");
    categoryId = category.dataset.categoryId;

    if (ignoreDeselect) {
        console.log("Ignoring");
        isCategoryDeselected = false;
    } else {
        console.log("Validating");
        isCategoryDeselected = categorySpan.classList.contains("active");
    }

    if (isCategoryDeselected) {
        hexColor = defaultHexColor;
        categoryId = -1;
    }
    
    isCategoryChangedWhileViewingGoal = window.location.pathname.includes("view-goal.php");

    removeActiveCategory();

    console.log("Setting " + category);
    console.log("isCategoryDeselected: " + isCategoryDeselected);
    if (!isCategoryDeselected || ignoreDeselect) {
        category.querySelector("span").classList.add("active");
        hexColor = category.querySelector(".color-square").style.backgroundColor;
    }

    // add color to all goal classes
    let goalElements = document.querySelectorAll(".goal");
    for (let goal of goalElements) {
        if (isCategoryDeselected) {
            goal.style.borderColor = defaultHexColor;
        } else {
            goal.style.borderColor = hexColor;
        }
    }
}

/**
 * Removes the active category.
 */
function removeActiveCategory() {
    let categories = document.querySelectorAll(".category");
    for (let category of categories) {
        category.querySelector("span").classList.remove("active");
    }
}
