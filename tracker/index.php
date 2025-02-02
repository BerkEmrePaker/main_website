<?php

session_start();

require($_SERVER['DOCUMENT_ROOT'] ."/berkemrepaker/navbar.php");

check_login_and_validate_access("tracker");

// Get current page URL 
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; 
$currentURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']; 

// Get server related info 
$user_ip_address = $_SERVER['REMOTE_ADDR']; 
$referrer_url = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'; 
$user_agent = $_SERVER['HTTP_USER_AGENT']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #35424a;
            color: #ffffff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #e8491d 3px solid;
        }
        header a {
            color: #ffffff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }
        header ul {
            padding: 0;
            list-style: none;
        }
        header li {
            float: left;
            display: inline;
            padding: 0 20px 0 20px;
        }
        header #branding {
            float: left;
        }
        header #branding h1 {
            margin: 0;
        }
        header nav {
            float: right;
            margin-top: 10px;
        }
        header .highlight, header .current a {
            color: #e8491d;
            font-weight: bold;
        }
        header a:hover {
            color: #cccccc;
            font-weight: bold;
        }
        #showcase {
            min-height: 400px;
            background: url('showcase.jpg') no-repeat 0 -400px;
            text-align: center;
            color: #ffffff;
        }
        #showcase h1 {
            margin-top: 100px;
            font-size: 55px;
            margin-bottom: 10px;
        }
        #showcase p {
            font-size: 20px;
        }
        .box {
            padding: 20px;
            margin: 20px 0;
            background: #ffffff;
            color: #333333;
            border: 1px solid #e8491d;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Server Info</h1>
            </div>
        </div>
    </header>
    <section id="showcase">
        <div class="container" style="color: black;">
            <h1>Welcome to Server Info Page</h1>
            <p>Below is the information gathered from the server and client.</p>
        </div>
    </section>
    <div class="container">
        <div class="box">
            <h2>Current Page URL</h2>
            <p><?php echo $currentURL; ?></p>
        </div>
        <div class="box">
            <h2>User IP Address</h2>
            <p><?php echo $user_ip_address; ?></p>
        </div>
        <div class="box">
            <h2>Referrer URL</h2>
            <p><?php echo $referrer_url; ?></p>
        </div>
        <div class="box">
            <h2>User Agent</h2>
            <p><?php echo $user_agent; ?></p>
        </div>
    </div>
</body>
</html>
