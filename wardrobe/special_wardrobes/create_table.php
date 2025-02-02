<?php
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
} else {
    echo "0 results";
}
// $wardrobe_con->close();



if (isset($_POST['table_creator'])) 
{
    // echo "valid";
    ///////////////////////////////////////////////////////////////////////////

    $table_name = $_POST['table_name'];

    // Validate the table name
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table_name)) {
        die("Invalid table name.");
    }

    

    // Check connection
    if ($wardrobe_con->connect_error) {
        die("Connection failed: " . $wardrobe_con->connect_error);
    }

    // Create the table
    $createTableSql = "CREATE TABLE `$table_name` (
      `ID` int(11) NOT NULL,
      `COLOUR` varchar(100) NOT NULL,
      `PHOTO` varchar(300) NOT NULL,
      `LOCATION` varchar(100) NOT NULL,
      `INFO` varchar(100) NOT NULL,
      `DATE_MODIFIED` datetime NOT NULL DEFAULT current_timestamp()
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

    // Execute the CREATE TABLE statement
    if ($wardrobe_con->query($createTableSql) === TRUE) {
        // Add the primary key
        $addPrimaryKeySql = "ALTER TABLE `$table_name` ADD PRIMARY KEY (`ID`);";
        if ($wardrobe_con->query($addPrimaryKeySql) === TRUE) {
            // Modify the ID column to be auto-increment
            $modifyIdSql = "ALTER TABLE `$table_name` MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";
            if ($wardrobe_con->query($modifyIdSql) === TRUE) {
                echo "Table `$table_name` created successfully.";
                
            } else {
                echo "Error modifying ID column: " . $wardrobe_con->error;
            }
        } else {
            echo "Error adding primary key: " . $wardrobe_con->error;
        }
    } else {
        echo "Error creating table: " . $wardrobe_con->error;
    }

    $wardrobe_con->close();
    header("Location: create_table.php");
    exit();


    ///////////////////////////////////////////////////////////////////////////
} else if (isset($_POST['table_choice'])) 
{
    echo "Table chosen to delete: " . $_POST['table_choice'];
    // Table name to be deleted
    $table_name = $_POST['table_choice'];

    // SQL query to delete the table
    $sql = "DROP TABLE IF EXISTS `$table_name`";

    // Execute the query
    if ($wardrobe_con->query($sql) === TRUE) 
    {
        echo "Table $table_name deleted successfully";
    } else 
    {
        echo "Error deleting table: " . $wardrobe_con->error;
    }

    // Close connection
    $wardrobe_con->close();
    header("Location: create_table.php");
    exit();
}

?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create or Delete Table</title>
    <?php navbar_head_style(); ?>
    <link rel="stylesheet" href="../../CSSstyles/wardrobe.css?v=<?php echo time(); ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
        }
        h2 {
            margin-top: 0;
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php navbar_body();?>
    <?php wardrobe_navbar();  ?>
    <div class="container">
        <h2>Create New Table:</h2>
        <form action="" method="post">
            <label>Enter Table Name: 
                <input type="text" name="table_name">
            </label>
            <input type="submit" name="table_creator" value="Submit">
        </form>
        <h2>Delete Table:</h2>
        <form action="" method="post">
            <?php
            // Find the index of the item to be deleted
            $key = array_search("main", $tableNames); //should be changed to 'main' in the future

            if ($key !== false) {
                // Remove the item at the found index
                unset($tableNames[$key]);
            }

            // Reindex the array if needed
            $tableNames = array_values($tableNames);

            foreach ($tableNames as $tableName) 
            {
                echo '<input type="submit" name="table_choice" value="' . htmlspecialchars($tableName) . '" onclick="return confirm(\'' . $tableName . ' will be deleted! This action is irreversible! Are you sure?\')"><br><br>';
            } ?>
        </form>
    </div>
</body>
</html>