<?php
$currentFile = $_SERVER['PHP_SELF'];
?>

<nav class="navbar navbar-expand bg-body-tertiary">
    <div class="container">
        <ul class="navbar-nav">
            <?php
            foreach ($sectionArray as $key => $section) {
                if ($key == $id) {
                    $currentId = $key;
                    $isActive = 'active';
                } else {
                    $isActive = '';
                }
                echo "<li class='nav-item'><a href='$currentFile?id=$key' class='nav-link $isActive'>{$section['title']}</a></li>";
            }
            ?>
        </ul>
    </div>
</nav>
