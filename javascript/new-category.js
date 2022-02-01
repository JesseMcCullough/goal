let newCategoryPopup = document.querySelector(".new-category");

let newCategoryPopupOverlay = document.querySelector(".new-category .overlay");
newCategoryPopupOverlay.addEventListener("click", onClickNewCategoryPopupOverlay);

let newCategoryAddButton = document.querySelector(".new-category .add-category");
newCategoryAddButton.addEventListener("click", onClickNewCategoryAddButton);

addOnClickEventToNewCategoryLink();

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
                addOnClickEventToAllCategories(setActiveCategory);

                // Set active category.
                let category = document.querySelector(".category.new"); 
                setActiveCategory(category, true);
            };
            categoriesRequest.send();
        }
    };
    newCategoryRequest.send();
}
