let newCategoryPopup = document.querySelector(".new-category");

let newCategoryPopupOverlay = document.querySelector(".new-category .overlay");
newCategoryPopupOverlay.addEventListener("click", onClickNewCategoryPopupOverlay);

let newCategoryAddButton = document.querySelector(".new-category .add-category");
newCategoryAddButton.addEventListener("click", onClickNewCategoryAddButton);

if (document.querySelector(".new-category-link")) {
    addOnClickEventToNewCategoryLink();
}

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
    document.body.style.overflowY = "hidden";
    window.scrollTo(0, 0);
}

/**
 * Hides the new category popup when the background/overlay is clicked.
 */
function onClickNewCategoryPopupOverlay() {
    newCategoryPopup.style.display = "none";
    document.body.style.overflowY = "initial";
}

/**
 * Adds a new category when the add category button is clicked.
 */
function onClickNewCategoryAddButton() {
    let categoryNameElement = document.querySelector(".new-category input[name='category-name']");
    let categoryName = categoryNameElement.value; // need to remember name for categoriesRequest.
    let categoryHexColor = document.querySelector(".new-category input[name='category-hex-color']");

    // Request a new category to be created.
    let requestUrl = null;
    let newCategoryParams = null;
    if (categoryId == -1) {
        // Request a new category to be created.
        requestUrl = "includes/category/new-category.php";
        newCategoryParams = "categoryName=" + encodeURIComponent(categoryNameElement.value)
                + "&categoryHexColor=" + encodeURIComponent(categoryHexColor.value);
    } else {
        // Request an existing category to be updated.
        requestUrl = "includes/category/edit-category.php";
        newCategoryParams = "categoryId=" + categoryId
                + "&categoryHexColor=" + encodeURIComponent(categoryHexColor.value);
        
        if (categoryNameElement.value != "") {
            newCategoryParams += "&categoryName=" + encodeURIComponent(categoryNameElement.value);
        }
    }
    let initialCategoryId = categoryId;

    let newCategoryRequest = new XMLHttpRequest();
    newCategoryRequest.open("POST", requestUrl, true);
    newCategoryRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    newCategoryRequest.onload = function() {
        if (this.status == 200) {
            // Reset inputs for new category.
            newCategoryPopup.style.display = "none";
            document.body.style.overflowY = "initial";
            categoryNameElement.value = "";
            categoryNameElement.placeholder = "New Category";
            categoryHexColor.value = "#000000";
            categoryId = this.responseText;
        }
    };
    newCategoryRequest.onloadend = function() {
        if (this.status == 200) {
            // Request all categories to show the newly added category.
            requestUrl = "includes/category/categories.php";
            let categoriesParams = "";

            if (categoryId != -1) {
                categoriesParams += "newCategory=" + encodeURIComponent(categoryName);
            }

            let categoriesRequest = new XMLHttpRequest();
            categoriesRequest.open("POST", requestUrl, true);
            categoriesRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            categoriesRequest.onload = function() {
                // Show categories.
                let categories = document.querySelectorAll(".categories");
                for (let category of categories) {
                    category.outerHTML = this.responseText;
                }
            };
            categoriesRequest.onloadend = function() {
                let categories = document.querySelectorAll(".categories");

                // Remove new category link from categories popup.
                let categoriesPopup = categories[0];
                categoriesPopup.querySelector(".new-category-link").remove();

                // Add event listeners.
                addOnClickEventToNewCategoryLink();
                addOnClickEventToAllCategories(setActiveCategory);

                let activeCategory = null;
                if (categoryId != -1) {
                    // Set active category.
                    activeCategory = document.querySelectorAll(".category.new")[1];
                    setActiveCategory(activeCategory, true);
                } else {
                    activeCategory = document.querySelector(".category");
                    if (!activeCategory) {
                        activeCategory = document.querySelector(".default-category");
                    }
                    setActiveCategory(activeCategory); 
                    setActiveCategory(activeCategory); // for deselect

                    let goals = document.querySelectorAll(".goal.click[data-category-id='" + initialCategoryId + "'");
                    for (let goal of goals) {
                        goal.dataset.categoryId = -1;
                        goal.dataset.categoryHexColor = "#ECECEC";
                    }
                }

                // Sort goals by category.
                if (typeof sortGoalsByCategory === "function") {
                    sortGoalsByCategory();
                    addOnClickEventToAllCategories(sortGoalsByCategory);
                }
            };
            categoriesRequest.send(categoriesParams);
        }
    };
    newCategoryRequest.send(newCategoryParams);
}
