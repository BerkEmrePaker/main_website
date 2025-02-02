<?php

session_start();
if ($_SESSION['username'] != "share") {
    header("Location: /berkemrepaker/index.php");
    die();
}

require("../../no_track/db_connections/file_share_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $target_dir = "../../no_track/uploads/file_share/";
    // $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/file_share/";
    $filename = basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO files (filename, filepath) VALUES (?, ?)");
        $stmt->bind_param("ss", $filename, $target_file);
        $stmt->execute();
        echo "The file " . htmlspecialchars($filename) . " has been uploaded.";
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Document</title>
</head>

<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        Select file to upload:
        <input type="file" name="file">
        <input type="submit" value="Upload File">
    </form>
</body>

</html>