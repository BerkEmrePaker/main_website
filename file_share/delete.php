<?php

session_start();
if ($_SESSION['username'] != "share") {
    die();
}


require("../../no_track/db_connections/file_share_connection.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT filepath FROM files WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($filepath);
    $stmt->fetch();
    $stmt->close();

    if (unlink($filepath)) {
        $stmt = $conn->prepare("DELETE FROM files WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: index.php");
    } else {
        echo "Error deleting file.";
    }
}
?>
