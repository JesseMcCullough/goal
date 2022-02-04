<?php 

include_once($_SERVER["DOCUMENT_ROOT"] . "/goals/git/classes/Category.php");

$isNewCategory = isset($_GET["newCategory"]); // active category. misleading name?

if (!isset($_SESSION)) {
    session_start();
}

$isNewCategoryAllowed = true;
if (isset($_GET["showNewCategory"])) {
    $isNewCategoryAllowed = filter_var($_GET["showNewCategory"], FILTER_VALIDATE_BOOLEAN);
}

?>

<!-- might need to make this a selection option for the form. -->
<ul class="categories">
    <li class="category default-category" data-category-id="-1">
        <div class="color-square" style="background-color: #ECECEC"></div>
        <span>Default</span>
    </li>
    <?php foreach (Category::getCategories($_SESSION["user_id"]) as $currentCategory) :?>
        <li class="category<?php if ($isNewCategory && $currentCategory->getName() == $_GET["newCategory"]) { echo " new"; } ?>"
            data-category-id="<?php echo $currentCategory->getId(); ?>">
            <div class="color-square" style="background-color: #<?php echo $currentCategory->getHexColor(); ?>"></div>
            <span <?php if ($isNewCategory && $currentCategory->getName() == $_GET["newCategory"]) { echo "class=\"active\""; }?>><?php echo $currentCategory->getName(); ?></span>
        </li>
    <?php endforeach; ?>
    <?php if ($isNewCategoryAllowed) :?>
        <li class="new-category-link">Edit Categories</li>
    <?php endif; ?>
</ul>

<?php 

unset($_GET["newCategory"], $_GET["showNewCategory"]);

?>