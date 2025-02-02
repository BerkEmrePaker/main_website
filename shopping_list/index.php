<?php

session_start();

require($_SERVER['DOCUMENT_ROOT'] . "/berkemrepaker/navbar.php");
require("../../no_track/db_connections/shopping_list_connection.php");

check_login_and_validate_access("shop_list");


function getShoppingListResult($conn)
{
    $query_string = "SELECT id, level, item, users_id FROM shop_items WHERE users_id = ? ORDER BY level ";
    $query = mysqli_prepare($conn, $query_string);
    mysqli_stmt_bind_param($query, "i", $_SESSION["userID"]);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    return $result;
}

function displayShoppingListItem($row)
{
    $level_string = htmlspecialchars($row['level']);
    $item_string = htmlspecialchars($row['item']);

    $delete_form = '
        <form method="POST" action="delete.php" style="display: inline-block;" onsubmit="return confirmDelete(\'' . htmlspecialchars($row['item'], ENT_QUOTES) . '\');">
            <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
            <button  type="submit" name="delete" class="button-delete">DELETE</button>
        </form>';

    $edit_form = '
        <form method="POST" action="edit.php" style="display: inline-block;">
            <input type="hidden" name="edit_id" value="' . htmlspecialchars($row['id']) . '">
            <button type="submit" name="edit" class="button-edit">EDIT</button>
        </form>';

    $output = "
        <tr class='c$level_string'>
            <td> $level_string </td>
            <td> $item_string </td>
            <td style='text-align: center;'> $edit_form  &nbsp;&nbsp;&nbsp;&nbsp; $delete_form </td>
        </tr>";

    return $output;
}

$result = getShoppingListResult($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List</title>
    <?php navbar_head_style(); ?>
    <link rel="stylesheet" href="/berkemrepaker/CSSstyles/shopping_index.css?v=<?php echo time(); ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>

<body>
    <?php navbar_body(); ?>
    <div id="main_div">
        <br><br>
        <div id="table_container">
            <table>
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Item</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo displayShoppingListItem($row);
                    } 
                    ?>
                </tbody>
            </table>
            <br><br>
            <div style="text-align: center;">
                <button id="add_button" href="add.php" class="add-button" role="button">Add New Item</button>
            </div>
            <br><br>
        </div>
    </div>

</body>

<script>
    $(document).ready(function () {
        $("#add_button").on("click", function () {
            document.location.href = "add.php";
        });

        $('.c1').css('background-color', '#DC143C');
        $('.c2').css('background-color', '#FF8C00');
        $('.c3').css('background-color', '#FFD700');
        $('.c4').css('background-color', '#32CD32');
    });

    function confirmDelete(item_text) {
        return confirm("Are you sure you want to delete " + item_text + "?");
    }
</script>

</html>