<?php 

// This script can be a request. As a safeguard, this script ensures the path to constants.php is correct before including it.
$includedFiles = get_included_files();
$hasConstants = false;

foreach ($includedFiles as $file) {
    if (strpos($file, "constants")) {
        $hasConstants = true;
        break;
    }
}

if (!$hasConstants) {
    include_once("../constants.php"); // Safeguard--this script can be a request.
}

include_once(CLASS_PATH . "Category.php");

$isNewCategory = isset($_POST["newCategory"]); // active category. misleading name?

if (!isset($_SESSION)) {
    session_start();
}

$isNewCategoryAllowed = true;
if (isset($_POST["showNewCategory"])) {
    $isNewCategoryAllowed = filter_var($_POST["showNewCategory"], FILTER_VALIDATE_BOOLEAN);
}

?>

<!-- might need to make this a selection option for the form. -->
<ul class="categories">
    <li class="category default-category" data-category-id="-1">
        <div class="color-square" style="background-color: #ECECEC"></div>
        <span>Default</span>
    </li>
    <?php foreach (Category::getCategories($_SESSION["user_id"]) as $currentCategory) :?>
        <li class="category<?php if ($isNewCategory && $currentCategory->getName() == $_POST["newCategory"]) { echo " new"; } ?>"
            data-category-id="<?php echo $currentCategory->getId(); ?>">
            <div class="color-square" style="background-color: <?php echo $currentCategory->getHexColor(); ?>"></div>
            <span <?php if ($isNewCategory && $currentCategory->getName() == $_POST["newCategory"]) { echo "class=\"active\""; }?>><?php echo $currentCategory->getName(); ?></span>
        </li>
    <?php endforeach; ?>
    <?php if ($isNewCategoryAllowed) :?>
        <li class="new-category-link">Edit Categories</li>
    <?php endif; ?>
    
</ul>

<?php 

unset($_POST["newCategory"], $_POST["showNewCategory"]);

?>