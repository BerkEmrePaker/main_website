<?php
session_start();

require($_SERVER['DOCUMENT_ROOT'] . "/berkemrepaker/navbar.php");
check_login_and_validate_access("wardrobe");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php navbar_head_style(); ?>
    <link rel="stylesheet" href="../CSSstyles/wardrobe.css?v=<?php echo time(); ?>">
    <title>Wardrobe</title>
</head>

<body>
    <?php navbar_body(); ?>
    <div class="container">
        <?php wardrobe_navbar(); ?>
        <div class="row" style="padding:50px;">

            <!-- Form to Add New Wardrobe Item -->
            <form action="add_to_system.php" method="post" autocomplete="off" enctype="multipart/form-data">
                <table width='100%' border='0'>
                    <tr>
                        <th>ID</th>
                        <th>Colour</th>
                        <th>Photo</th>
                        <th>Location</th>
                        <th>Info</th>
                        <th>Date Uploaded</th>
                    </tr>
                    <tr>
                        <td>AUTO</td>
                        <td><input type="text" name="COLOUR" required></td>
                        <td><input type="file" name="PHOTO" accept=".jpg, .jpeg, .png" required></td>
                        <td><input type="text" name="LOCATION" required></td>
                        <td><input type="text" name="INFO" required></td>
                        <td>AUTO</td>
                    </tr>
                </table>
                <br><br><br>
                <input type="submit" name="add_button" value="Add to Database">
            </form>
        </div>
    </div>
</body>

</html>