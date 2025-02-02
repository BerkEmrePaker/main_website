<?php
session_start();

// Include necessary files
require($_SERVER['DOCUMENT_ROOT'] . "/berkemrepaker/navbar.php");
require('../../no_track/db_connections/wardrobe_connection.php');

// Validate user access
check_login_and_validate_access("wardrobe");

// Handle deletion request
if (isset($_POST['delete_button']) && isset($_POST['delete_choice'])) {
    delete_wardrobe_item($wardrobe_con, $_POST['delete_choice'], $_POST['image_to_delete']);
}

/**
 * Delete a wardrobe item and its associated image.
 *
 * @param mysqli $db Database connection
 * @param int $id_to_delete ID of the item to delete
 * @param string $filename_to_delete Image filename to delete
 */
function delete_wardrobe_item($db, $id_to_delete, $filename_to_delete) {
    $file_path = $_SERVER['DOCUMENT_ROOT'] . "/no_track/images/wardrobe_images/" . $filename_to_delete;

    // Prepare and execute deletion query
    $stmt = $db->prepare("DELETE FROM main WHERE ID = ?");
    $stmt->bind_param("s", $id_to_delete);

    if ($stmt->execute()) {
        if (file_exists($file_path) && !unlink($file_path)) {
            echo "Error deleting the file.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $db->close();
    header("Location: display_wardrobe.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php navbar_head_style(); ?>
    <link rel="stylesheet" href="../CSSstyles/wardrobe.css?v=<?php echo time(); ?>">
    <title>Delete Wardrobe Item</title>
    <style>
        tr.label-row {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php navbar_body(); ?>
    <div class="container">
        <?php wardrobe_navbar(); ?>
        <div class="row" style="padding:50px;">
            <form action="" method="post" autocomplete="off">
                <table width='100%' border='0'>
                    <tr>
                        <th>Delete</th>
                        <th>ID</th>
                        <th>Colour</th>
                        <th>Photo</th>
                        <th>Location</th>
                        <th>Info</th>
                        <th>Date Uploaded</th>
                    </tr>
                    <?php display_wardrobe_items($wardrobe_con, true); ?>
                </table>
                <br><br><br>
                <input type="submit" name="delete_button" value="Delete Row">
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('tr.label-row').forEach(row => {
            row.addEventListener('click', function () {
                var radioId = this.getAttribute('data-radio-id');
                var radio = document.getElementById(radioId);
                if (radio) {
                    radio.checked = true;
                }
            });
        });
    </script>
</body>

</html>

<?php
/**
 * Fetch and display wardrobe items with a delete option.
 *
 * @param mysqli $db Database connection
 * @param bool $includeDeleteOption Whether to include delete radio buttons
 */
function display_wardrobe_items($db, $includeDeleteOption = false) {
    $query = $db->query("SELECT * FROM main ORDER BY DATE_MODIFIED DESC");
    $count = 0;

    while ($row = $query->fetch_object()) {
        $count++;
        echo "<tr class='label-row' data-radio-id='option{$count}'>";
        if ($includeDeleteOption) {
            echo "<td><input type='radio' name='delete_choice' value='{$row->ID}' id='option{$count}'></td>";
        }
        echo "<td>{$row->ID}</td>";
        echo "<td>{$row->COLOUR}</td>";
        echo "<td><img src='/no_track/images/wardrobe_images/{$row->PHOTO}' alt='{$row->INFO}' width='100'></td>";
        echo "<input type='hidden' name='image_to_delete' value='{$row->PHOTO}'>";
        echo "<td>{$row->LOCATION}</td>";
        echo "<td>{$row->INFO}</td>";
        echo "<td>{$row->DATE_MODIFIED}</td>";
        echo "</tr>";
    }
}
?>
