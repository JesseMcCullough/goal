<?php 

include_once($_SERVER["DOCUMENT_ROOT"] . "/goals/git/classes/Category.php");

$isNewCategory = isset($_GET["newCategory"]); // active category. misleading name?

if (!isset($_SESSION)) {
    session_start();
}

?>

<!-- might need to make this a selection option for the form. -->
<ul class="categories">
    <?php foreach (Category::getCategories($_SESSION["user_id"]) as $currentCategory) :?>
        <li class="category<?php if ($isNewCategory && $currentCategory->getName() == $_GET["newCategory"]) { echo " new"; } ?>"
            data-category-id="<?php echo $currentCategory->getId(); ?>">
            <div class="color-square" style="background-color: #<?php echo $currentCategory->getHexColor(); ?>"></div>
            <span <?php if ($isNewCategory && $currentCategory->getName() == $_GET["newCategory"]) { echo "class=\"active\""; }?>><?php echo $currentCategory->getName(); ?></span>
        </li>
    <?php endforeach; ?>
    <li class="new-category-link">New Category</li>
</ul>
