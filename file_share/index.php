<?php

session_start();
if ($_SESSION['username'] != "share") {
    header("Location: /berkemrepaker/index.php");
    die();
}

require("../../no_track/db_connections/file_share_connection.php");

$sql = "SELECT * FROM files";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSSstyles/file_share.css">
    <title>File Share</title>
</head>

<body>
    <h1>Shared Files</h1>
    <a href="upload.php">Upload New File</a>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <a href="download.php?file=<?= $row['filename'] ?>"><?= $row['filename'] ?></a>
                <a href="delete.php?id=<?= $row['id'] ?>">Delete</a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>

</html>