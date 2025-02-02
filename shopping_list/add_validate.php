<?php

session_start();

require($_SERVER['DOCUMENT_ROOT'] . "/berkemrepaker/navbar.php");
require("../../no_track/db_connections/shopping_list_connection.php");

check_login_and_validate_access("shop_list");

if (isset($_POST['level']) && isset($_POST['item'])) {
    $level = $_POST['level'];
    $item = $_POST['item'];
    $user_id = $_SESSION['userID'];
    if ($level > 4) {
        $level = 4;
    } else if ($level < 1) {
        $level = 1;
    }

    $sql = "INSERT INTO shop_items (`level`, `item`, `users_id`) VALUES (?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isi", $level, $item, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Item successfully added.";
        } else {
            echo "Unsuccesful add operation!";
        }

        $stmt->close();
        $conn->close();
        header("Location: index.php");
    }
} else {
    header("Location: add.html");
    die();
}

?>