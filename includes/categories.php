<?php 

include_once($_SERVER["DOCUMENT_ROOT"] . "/goals/git/classes/Category.php");

$isNewCategory = isset($_GET["newCategory"]);

?>

<!-- might need to make this a selection option for the form. -->
<ul class="categories">
    <?php foreach (Category::getCategories() as $category) :?>
        <li class="category<?php if ($isNewCategory && $category->getName() == $_GET["newCategory"]) { echo " new"; } ?>">
            <div class="color-square" style="background-color: #<?php echo $category->getHexColor(); ?>"></div>
            <span><?php echo $category->getName(); ?></span>
        </li>
    <?php endforeach; ?>
    <li class="new-category-link">New Category</li>
</ul>
