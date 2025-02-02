<?php

session_start();

require($_SERVER['DOCUMENT_ROOT'] . "/berkemrepaker/navbar.php");
require("../../no_track/db_connections/shopping_list_connection.php");

check_login_and_validate_access("shop_list");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adf New Item To Shopping List</title>
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
            <legend>Add a New Item</legend>
            <form action="add_validate.php" method="post" data-validate="parsley">
                <div class='row'>
                    <label for='level'>Enter Level</label>
                    <input type="text" placeholder="Enter Level" name='level' id='level' min="1" max="4" required
                        data-parsley-required="true" data-parsley-type="number" data-parsley-min="1"
                        data-parsley-max="4" data-error-message="Please enter a level between 1 and 4.">
                </div>
                <div class='row'>
                    <label for="item">Enter Item</label>
                    <input type="text" placeholder="Enter Item" name='item' id='item' required
                        data-parsley-required="true" data-error-message="The item field is required.">
                </div>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
</body>

</html>