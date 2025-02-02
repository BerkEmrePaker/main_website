<?php

function check_login()
{
    if (!isset($_SESSION['username'])) {
        // User is not logged in, redirect to login page
        header("Location: login.php");
        exit();
    }
}


function check_login_and_validate_access($string)
{
    if (!isset($_SESSION['access'])) {
        // Full URL path
        $url = $_SERVER['REQUEST_URI'];

        // The segment after which you want to extract the remaining part
        $segment = 'berkemrepaker/';

        // Find the position of the segment in the URL
        $position = strpos($url, $segment);

        // Extract the part after the segment
        if ($position !== false) {
            $pathAfterSegment = substr($url, $position + strlen($segment));
        } else {
            $pathAfterSegment = ''; // Segment not found in the URL
        }

        $_SESSION['initial_visited_page'] = $pathAfterSegment;
        header("Location: /berkemrepaker/special_cases/bad_permissions.php");
        die();
    }

    $array = $_SESSION['access'];
    if (!in_array($string, $array)) {
        echo "String does not exist in the array.";
        // do not allow access to webpage, redirect to index.php
        header("Location: /berkemrepaker/index.php");
        die();
    }
}

function navbar_head_style()
{
    echo '
    <head>
    <link rel="shortcut icon" href="/berkemrepaker/images/favicon.ico" type="image/x-icon">
        <style>
            body {
                margin: 0;
                font-family: Arial, sans-serif;
                background-color: #0B1215;
                /* #f4f4f4 ---- #0B1215 */
            }
            
            .navbar {
                background-color: #333;
                overflow: hidden;
            }
            
            .nav-list {
                list-style-type: none;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .nav-item {
                padding: 14px 20px;
            }
            
            .nav-item a {
                color: white;
                text-decoration: none;
                display: block;
            }
            
            .nav-item a:hover {
                color: red;
            }
            
            .welcome {
                color: white;
            }
        </style>
    </head>';

    function navbar_body()
    {
        echo '
                <nav class="navbar">
                    <ul class="nav-list">
                        <li class="nav-item"><a href="/berkemrepaker/index.php">HOME</a></li>
                        <li class="nav-item welcome">Welcome back, ' . $_SESSION['username'] . '!</li>
                        <li class="nav-item"><a href="/berkemrepaker/exit.php">Log OUT</a></li>
                    </ul>
                </nav>
            
            ';
    }
}

function wardrobe_navbar()
{
    echo '<nav class="navbar" style="background-color: #4CAF50;">
        <ul class="nav-list">
            <li class="nav-item"><a href="/berkemrepaker/wardrobe/display_wardrobe.php">WARDROBE</a></li>
            <li class="nav-item"><a href="/berkemrepaker/wardrobe/searchbar/search_fe.php">SEARCH</a></li>
            <li class="nav-item"><a href="/berkemrepaker/wardrobe/add_clothes.php">ADD</a></li>
            <li class="nav-item"><a href="/berkemrepaker/wardrobe/delete_row.php">DELETE</a></li>
            <li class="nav-item"><a href="/berkemrepaker/wardrobe/special_wardrobes/special_index.php">OTHER WARDROBES</a></li>
        </ul>
    </nav>';
}

?>