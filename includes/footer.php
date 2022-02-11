</div>

<?php

/*
 * Generates a page's ending <body> and <html> tags, while also inserting the
 * JavaScript files added by header.php's addJavaScript(file).
 */

if (!empty($events)) {
    echo "<script>";
    echo "window.dataLayer = window.dataLayer || [];";
    
    foreach ($events as $event) {
        echo "window.dataLayer.push({'event': '" . $event . "'});";
    }

    echo "</script>";
}

foreach ($scripts as $script) {
    echo '<script src="javascript/' . $script . '.js"></script>';
}

?>

</body>
</html>
