<?php
session_start();

// Include necessary files
require($_SERVER['DOCUMENT_ROOT'] . "/berkemrepaker/navbar.php");
require('../../no_track/db_connections/wardrobe_connection.php');

// Validate user access
check_login_and_validate_access("wardrobe");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php navbar_head_style(); ?>
    <link rel="stylesheet" href="../CSSstyles/wardrobe.css?v=<?php echo time(); ?>">
    <title>Wardrobe Display</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type='text/javascript'>
        $(document).ready(function () {
            // Show input element on click
            $('.edit').click(function () {
                $('.txtedit').hide();
                $(this).next('.txtedit').show().focus();
                $(this).hide();
            });

            // Save data on focus out
            $(".txtedit").focusout(function () {
                var id = this.id;
                var split_id = id.split("_");
                var field_name = split_id[0];
                var edit_id = split_id[1];
                var value = $(this).val();

                $(this).hide();
                $(this).prev('.edit').show().text(value);

                $.ajax({
                    url: 'update.php',
                    type: 'post',
                    data: { field: field_name, value: value, id: edit_id },
                    success: function (response) {
                        console.log(response == 1 ? 'Save successfully' : 'Not saved.');
                        console.log(response);
                    }
                });
            });
        });
    </script>
</head>

<body>
    <?php navbar_body(); ?>
    <div class="container">
        <?php wardrobe_navbar(); ?>
        <div class="row" style="padding:50px;">
            <table width='100%' border='0'>
                <tr>
                    <th>ID</th>
                    <th>Colour</th>
                    <th>Photo</th>
                    <th>Location</th>
                    <th>Info</th>
                    <th>Date Uploaded</th>
                </tr>
                <?php display_wardrobe_items($wardrobe_con); ?>
            </table>
        </div>
    </div>
    <style>
        .edit { width: 100%; height: 25px; }
        .editMode { border: 1px solid black; }
        .txtedit { display: none; width: 99%; height: 30px; }
    </style>
</body>
</html>

<?php
/**
 * Fetch and display wardrobe items from the database.
 *
 * @param mysqli $db Database connection
 */
function display_wardrobe_items($db) {
    $query = $db->query("SELECT * FROM main ORDER BY DATE_MODIFIED DESC");
    while ($row = $query->fetch_object()) {
        echo "<tr>";
        echo "<td>{$row->ID}</td>";
        echo render_editable_cell("colour", $row->COLOUR, $row->ID);
        echo render_photo_cell($row->PHOTO, $row->INFO);
        echo render_editable_cell("location", $row->LOCATION, $row->ID);
        echo render_editable_cell("info", $row->INFO, $row->ID);
        echo render_editable_cell("date", $row->DATE_MODIFIED, $row->ID);
        echo "</tr>";
    }
}

/**
 * Render an editable text cell.
 *
 * @param string $field Field name
 * @param string $value Field value
 * @param int $id Record ID
 * @return string HTML for the editable cell
 */
function render_editable_cell($field, $value, $id) {
    return "<td>
                <div class='edit'>{$value}</div>
                <input type='text' class='txtedit' value='{$value}' id='{$field}_{$id}'>
            </td>";
}

/**
 * Render a photo cell with a clickable button.
 *
 * @param string $photo Photo filename
 * @param string $info Image alt text
 * @return string HTML for the photo cell
 */
function render_photo_cell($photo, $info) {
    return "<td>
                <form action='change_image.php' method='post'>
                    <button type='submit' name='image_to_change' value='{$photo}' style='border: 0px; padding:0px;'>
                        <img src='../../no_track/images/wardrobe_images/{$photo}' alt='{$info}' width='100'>
                    </button>
                </form>
            </td>";
}
?>
