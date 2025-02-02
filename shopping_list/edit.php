<?php

session_start();

require($_SERVER['DOCUMENT_ROOT'] . "/berkemrepaker/navbar.php");
require("../../no_track/db_connections/shopping_list_connection.php");

check_login_and_validate_access("shop_list");

// handling sending of initial ID
if (isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $query_string = "SELECT id, level, item, users_id FROM shop_items WHERE id = ? AND users_id = ? ";
    $query = mysqli_prepare($conn, $query_string);
    mysqli_stmt_bind_param($query, "ii", $edit_id, $_SESSION["userID"]);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    $level = "an error has occured";
    $item = "an error has occured";
    while ($row = mysqli_fetch_assoc($result)) {

        $level = $row['level'];
        $item = $row['item'];
    }
} else {
    header("Location: index.php");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <link rel="stylesheet" href="../CSSstyles/shopping_add.css">
    <script>

        function placeholderIsSupported() {
            test = document.createElement('input');
            return ('placeholder' in test);
        }

        $(document).ready(function () {
            placeholderSupport = placeholderIsSupported() ? 'placeholder' : 'no-placeholder';
            $('html').addClass(placeholderSupport);
        });
    </script>
</head>

<body>
    <div id="registration-form">
        <div class='fieldset'>
            <legend> Existing Item</legend>
            <form action="edit_validate.php" method="post" data-validate="parsley">
                <div class='row'>
                    <label for='level'>Enter Level</label>
                    <input type="text" placeholder="<?php echo $level?>" value="<?php echo $level?>" name='confirm_level' id='level' min="1" max="4" required
                        data-parsley-required="true" data-parsley-type="number" data-parsley-min="1"
                        data-parsley-max="4" data-error-message="Please enter a level between 1 and 4.">
                </div>
                <div class='row'>
                    <label for="item">Enter Item</label>
                    <input type="text" placeholder="<?php echo $item?>" value="<?php echo $item?>" name='confirm_item' id='item' required
                        data-parsley-required="true" data-error-message="The item field is required.">
                </div>
                <input type="hidden" name="confirm_id" value="<?php echo htmlspecialchars($edit_id)?>">
                <input type="submit" value="Edit">
            </form>
        </div>
    </div>
</body>

</html>