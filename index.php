<?php
// start session
session_start();
//require a connection to navbar to access the header navigation bar
require($_SERVER['DOCUMENT_ROOT'] . "/berkemrepaker/navbar.php");
// check login status
check_login();



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berk Emre Paker - Welcome</title>

    <?php navbar_head_style(); ?>
    <link rel="stylesheet" href="CSSstyles/index.css">
</head>

<body>
    <?php navbar_body(); ?>
    <br><br><br><br>
    <div class="mainframe">
        <div class="links">
            <h2><a href="portfolio/portfolio.php">PORTFOLIO</a></h2>

            <h2><a href="todolist/todolist.php">To Do List</a></h2>

            <h2><a href="shopping_list/index.php">Shopping List</a></h2>

            <h2><a href="wardrobe/display_wardrobe.php">WARDROBE</a></h2>

            <h2><a href="file_share/index.php">Main File Share</a></h2>
            <h2>...</h2>
            <h2><a href="tracker/index.php">You are being tracked</a></h2>
        </div>
    </div>
</body>

</html>