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
    let categoryNameElement = document.querySelector("input[name='category-name'");
    let categoryColorElement = document.querySelector("input[name='category-hex-color'")
    categoryId = category.dataset.categoryId;

    if (ignoreDeselect) {
        isCategoryDeselected = false;
    } else {
        isCategoryDeselected = categorySpan.classList.contains("active");
    }

    if (isCategoryDeselected) {
        hexColor = defaultHexColor;
        categoryId = -1;
        categoryNameElement.value = "";
        categoryNameElement.placeholder = "New Category";
        categoryColorElement.value = "#000000";
    }
    
    isCategoryChangedWhileViewingGoal = window.location.pathname.includes("view-goal.php");

    removeActiveCategory();
    
    if (!isCategoryDeselected || ignoreDeselect) {
        // add active tag to popup category and main category
        for (let category of document.querySelectorAll(".category[data-category-id='" + categoryId + "']")) {
            category.querySelector("span").classList.add("active");
        }
        
        hexColor = convertRGBToHex(category.querySelector(".color-square").style.backgroundColor);

        categoryNameElement.value = categorySpan.textContent;
        categoryNameElement.placeholder = categorySpan.textContent;
        categoryNameElement.style.borderColor = "#ECECEC";
        categoryColorElement.value = hexColor;
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

function convertRGBToHex(rgb) {
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

    function hex(x) {
        return ("0" + parseInt(x).toString(16)).slice(-2);
    }

    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}
