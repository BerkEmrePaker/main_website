<?php
session_start();

require($_SERVER['DOCUMENT_ROOT'] . "/berkemrepaker/navbar.php");

check_login_and_validate_access("wardrobe");
require('../../../no_track/db_connections/wardrobe_connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php navbar_head_style(); ?>
    <link rel="stylesheet" href="../../CSSstyles/wardrobe.css?v=<?php echo time(); ?>">
    <title>Wardrobe</title>



</head>

<body>
    <?php navbar_body(); ?>
    <div class="container">
        <?php wardrobe_navbar(); ?>
        <div class="row" style="padding:50px;">
            <table width='100%' border='0'>

                <tr>
                    <th id="ID">ID</th>
                    <th id="Colour">Colour</th>
                    <th id="Photo">Photo</th>
                    <th id="Location">Location</th>
                    <th id="Info">Info</th>
                    <th id="Date_Uploaded">Date Uploaded</th>
                </tr>
                <?php

                $count = 1;
                $query = $wardrobe_con->query("SELECT * FROM " . $_POST['table_choice'] . " order by ID"); // this is where i simply change the input ::::: SELECT * FROM main  WHERE ID IN (1, 2, 3, 22) ORDER BY ID;
                // make sure to add security here!!!!
                while ($row = $query->fetch_object()) {
                    $id = $row->ID;
                    $colour = $row->COLOUR;
                    $photo = $row->PHOTO;
                    $location = $row->LOCATION;
                    $info = $row->INFO;
                    $date = $row->DATE_MODIFIED;
                    ?>
                    <tr>
                        <td> <?php echo $id; ?> </td>
                        <td> <?php echo $colour; ?> </td>
                        <td> <?php echo "<img src='/no_track/images/wardrobe_images/$photo' alt='$info' width='100'>"; ?>
                        </td>
                        <td> <?php echo $location; ?> </td>
                        <td> <?php echo $info ?> </td>
                        <td> <?php echo $date; ?> </td>
                    </tr>
                    <?php
                } // very important do NOT remove the bracket
                ?>
            </table>
        </div>
    </div>
    <style>
        .edit {
            width: 100%;
            height: 25px;
        }

        .editMode {
            border: 1px solid black;
        }

        .txtedit {
            display: none;
            width: 99%;
            height: 30px;
        }
    </style>
</body>

</html>