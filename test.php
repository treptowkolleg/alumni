<?php

$id = $_GET['id'] ?? 0;

$sectionArray = [
    ['template' => 'start.php', 'title' =>  'Startseite'],
    ['template' => 'about.php', 'title' =>  'Steckbrief'],
    ['template' => 'links.php', 'title' =>  'Nützliche Links']
];

?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $sectionArray[$id]['title'] ?></title>
        <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
                rel="stylesheet"
                integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
                crossorigin="anonymous"
        >
    </head>
    <body>
        <header>
            <?php require('navigation.php') ?>
        </header>
        <main class="container py-3">
            <h1><?= $sectionArray[$id]['title'] ?></h1>
            <?php require( $sectionArray[$id]['template'] ) ?>
        </main>
    </body>
</html>
