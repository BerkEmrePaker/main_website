<?php

session_start();

require($_SERVER['DOCUMENT_ROOT'] . "/berkemrepaker/navbar.php");
require("../../no_track/db_connections/shopping_list_connection.php");

check_login_and_validate_access("shop_list");

if (isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT)) {
    $item_id = $_POST['id'];
    $user_id = $_SESSION['userID'];

    $sql = "DELETE FROM shop_items WHERE id = ? AND users_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $item_id, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Item successfully deleted.";
        } else {
            echo "No matching item found or you do not have permission to delete this item.";
        }

        $stmt->close();
    } else {
        echo "Failed to prepare the statement.";
    }
} else {
    echo "No valid item selected to be deleted! Please select an item to delete.";
}

header("Location: index.php");
die();
?>