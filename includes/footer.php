</div>

<?php

/*
 * Generates a page's ending <body> and <html> tags, while also inserting the
 * JavaScript files added by header.php's addJavaScript(file).
 */

foreach ($scripts as $script) {
    echo '<script src="javascript/' . $script . '.js"></script>';
}

?>

</body>
</html>
