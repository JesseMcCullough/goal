let hexColor = "#ECECEC"; // Hex color used for goal and step border color; changes by user input.
let categoryId = -1;

let newCategoryPopup = document.querySelector(".new-category");

let newCategoryPopupOverlay = document.querySelector(".new-category .overlay");
newCategoryPopupOverlay.addEventListener("click", onClickNewCategoryPopupOverlay);

let newCategoryAddButton = document.querySelector(".new-category .add-category");
newCategoryAddButton.addEventListener("click", onClickNewCategoryAddButton);

addOnClickEventToNewCategoryLink();
addOnClickEventToAllCategories();

/**
 * Adds a click event to the new category link.
 */
function addOnClickEventToNewCategoryLink() {
    let newCategoryLink = document.querySelector(".new-category-link");
    newCategoryLink.addEventListener("click", onClickNewCategory);
}

/**
 * Shows the new category popup when the new category link is clicked.
 */
function onClickNewCategory() {
    newCategoryPopup.style.display = "block";
}

/**
 * Hides the new category popup when the background/overlay is clicked.
 */
function onClickNewCategoryPopupOverlay() {
    newCategoryPopup.style.display = "none";
}

/**
 * Adds a new category when the add category button is clicked.
 */
function onClickNewCategoryAddButton() {
    let categoryNameElement = document.querySelector(".new-category input[name='category-name']");
    let categoryName = categoryNameElement.value; // need to remember name for categoriesRequest.
    let categoryHexColor = document.querySelector(".new-category input[name='category-hex-color']");

    // Request a new category to be created.
    let newCategoryRequest = new XMLHttpRequest();
    newCategoryRequest.open("GET", "includes/new-category.php?categoryName=" + categoryNameElement.value
            + "&categoryHexColor=" + categoryHexColor.value.replace("#", ""), true);
    newCategoryRequest.onload = function() {
        if (this.status == 200) {
            // Reset inputs for new category.
            newCategoryPopup.style.display = "none";
            categoryNameElement.value = "";
            categoryHexColor.value = "#000000";
        }
    };
    newCategoryRequest.onloadend = function() {
        if (this.status == 200) {
            // Request all categories to show the newly added category.
            let categoriesRequest = new XMLHttpRequest();
            categoriesRequest.open("GET", "includes/categories.php?newCategory=" + categoryName, true);
            categoriesRequest.onload = function() {
                // Show categories.
                let categories = document.querySelector(".categories");
                categories.outerHTML = this.responseText;

                // Add event listeners.
                addOnClickEventToNewCategoryLink();
                addOnClickEventToAllCategories();

                // Set active category.
                let category = document.querySelector(".category.new"); 
                setActiveCategory(category);
            };
            categoriesRequest.send();
        }
    };
    newCategoryRequest.send();
}

/**
 * Adds a click event to all the categories.
 */
function addOnClickEventToAllCategories() {
    let categories = document.querySelectorAll(".category");

    for (let category of categories) {
        category.addEventListener("click", function() {
            setActiveCategory(category);
        });
    }
}

/**
 * Sets the active category.
 */
function setActiveCategory(category) {
    removeActiveCategory();
    category.querySelector("span").classList.add("active");
    hexColor = category.querySelector(".color-square").style.backgroundColor;
    categoryId = category.dataset.categoryId;
    // add color to all goal classes

    let goalElements = document.querySelectorAll(".goal");
    for (let goal of goalElements) {
        goal.style.borderColor = hexColor;
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