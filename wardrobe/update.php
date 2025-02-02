<?php
require('../../no_track/db_connections/wardrobe_connection.php');

// Check if required POST parameters are set
if (isset($_POST['field']) && isset($_POST['value']) && isset($_POST['id'])) {
    $field = $_POST['field'];
    $value = $_POST['value'];
    $editid = $_POST['id'];

    // Validate and sanitize input
    $allowed_fields = ['colour', 'location', 'info']; // Define allowed fields
    if (!in_array($field, $allowed_fields)) {
        echo 0;
        exit;
    }

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $wardrobe_con->prepare("UPDATE main SET $field = ? WHERE ID = ?");
    $stmt->bind_param("si", $value, $editid);
    
    // Execute the update query
    if ($stmt->execute()) {
        echo 1;
    } else {
        echo -1;
    }

    // Close statement and connection
    $stmt->close();
} else {
    echo -2;
}
exit;
?>
