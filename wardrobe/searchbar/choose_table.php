<?php // there shoudl be no 'berk' table and follow paper design
// start session
session_start();
//require a connection to navbar to access the header navigation bar
require($_SERVER['DOCUMENT_ROOT'] ."/berkemrepaker/navbar.php");
require('../../../no_track/db_connections/wardrobe_connection.php');
// check login status
check_login_and_validate_access("wardrobe");

// Query to get the table names
$sql = "SHOW TABLES";
$result = $wardrobe_con->query($sql);

$tableNames = [];

if ($result->num_rows > 0) {
    // Fetch all table names
    while($row = $result->fetch_array()) {
        $tableNames[] = $row[0];
    }
    // Find the index of the item to be deleted
    $key = array_search("main", $tableNames); //should be changed to 'main' in the future

    if ($key !== false) {
        // Remove the item at the found index
        unset($tableNames[$key]);
    }

    // Reindex the array if needed
    $tableNames = array_values($tableNames);
} else {
    echo "0 results";
}
$wardrobe_con->close();

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Wardrobes</title>
    
    <?php navbar_head_style();?>
    <link rel="stylesheet" href="../../CSSstyles/wardrobe.css?v=<?php echo time(); ?>">
    
    <style>
        .mainframe {
            padding: 20px;
        }

        .links {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .links h2 {
            margin: 20px 0;
        }

        .links a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .links a:hover {
            color: #007BFF;
        }
    </style>
</head>
<body>
    <?php navbar_body();?>
    <?php wardrobe_navbar();  ?>
    <br><br>
    <h2 style="width: 100%; text-align: center; color: white;">Choose Table To SEARCH</h2>
    <br>
    <div class="mainframe">
        <div class="links">
            <form action="search_fe.php" method="post">
            <?php
            foreach ($tableNames as $tableName) 
            {
                echo '<input type="submit" name="table_choice" value="' . htmlspecialchars($tableName) . '"><br><br>'; // special chars may cause a problem in the future
            } ?>
            </form>
            <h2 style="text-align: center;"><a style="color: #46e871;" href="/berkemrepaker/wardrobe/special_wardrobes/create_table.php">Create New Table</a>   &nbsp&nbsp&nbsp   <a style="color: red;" href="/berkemrepaker/wardrobe/special_wardrobes/create_table.php">Delete a Table</a></h2>
        </div>
        
    </div>
</body>
</html>
