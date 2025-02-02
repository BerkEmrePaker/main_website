<?php

session_start();

require($_SERVER['DOCUMENT_ROOT'] . "/berkemrepaker/navbar.php");
require("../../no_track/db_connections/shopping_list_connection.php");

check_login_and_validate_access("shop_list");

if (
    isset($_POST['confirm_id']) && filter_var($_POST['confirm_id'], FILTER_VALIDATE_INT)
    && isset($_POST['confirm_level'])
    && isset($_POST['confirm_item'])
) {
    $item_id = $_POST['confirm_id'];
    $level = $_POST['confirm_level'];
    $item = $_POST['confirm_item'];
    $user_id = $_SESSION['userID'];

    if ($level > 4) {
        $level = 4;
    } else if ($level < 1) {
        $level = 1;
    }

    $sql = "UPDATE shop_items SET `item` = ?, `level` = ? WHERE id = ? AND users_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("siii", $item, $level, $item_id, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Item successfully updated.";
        } else {
            echo "No matching item found or you do not have permission to update this item.";
        }

        $stmt->close();
    } else {
        echo "Failed to prepare the statement.";
    }
} else {
    echo "No valid item selected to be updated! Please select an item to update.";
}

header("Location: index.php");
die();
?>