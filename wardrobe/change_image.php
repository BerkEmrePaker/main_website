<?php

session_start();

require($_SERVER['DOCUMENT_ROOT'] . "/berkemrepaker/navbar.php");
require('../../no_track/db_connections/wardrobe_connection.php');
check_login_and_validate_access("wardrobe");


if (!isset($_POST['image_to_change']) && !isset($_POST['add_button'])) {
    die();
}


if (isset($_POST['add_button'])) {


    if ($_FILES['PHOTO']["error"] == 4) {
        echo
            "<script> alert('Image does not exist'); </script>";
    } else {
        $tmpName = $_FILES['PHOTO']["tmp_name"];
        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $fileNamePHOTO = $_FILES['PHOTO']["name"];
        $fileSizePHOTO = $_FILES['PHOTO']["size"];
        $fileNamePHOTO = explode('.', $fileNamePHOTO);
        $imageExtensionPHOTO = strtolower(end($fileNamePHOTO));

        if (!in_array($imageExtensionPHOTO, $validImageExtension)) {
            echo
                "<script> 
                alert('Invalid Image Extension'); 
            </script>";
        } else {
            echo "Valid Image Extension<br>";
            $newImageNamePHOTO = uniqid();
            $newImageNamePHOTO .= '.' . $imageExtensionPHOTO;
            move_uploaded_file($tmpName, $_SERVER['DOCUMENT_ROOT'] . "/no_track/images/wardrobe_images/" . $newImageNamePHOTO);
            echo $newImageNamePHOTO;
            $file_path = $_SERVER['DOCUMENT_ROOT'] . "/no_track/images/wardrobe_images/" . $_POST['original_photo'];
            if (file_exists($file_path)) {
                if (unlink($file_path)) {
                    echo "File deleted successfully.";
                } else {
                    echo "Error deleting the file.";
                }
            } else {
                echo "File does not exist.";
            }
        }
    }

    // Prepare and bind
    $stmt = $wardrobe_con->prepare("UPDATE main SET PHOTO = (?) WHERE PHOTO = ? ");
    $stmt->bind_param("ss", $newImageNamePHOTO, $_POST['original_photo']);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $wardrobe_con->close();
    header("Location: display_wardrobe.php");
    die();
} else {
    $prechange_image = htmlspecialchars($_POST['image_to_change']);
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Image</title>
    <?php navbar_head_style(); ?>
    <link rel="stylesheet" href="../CSSstyles/wardrobe.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php navbar_body(); ?>
    <?php wardrobe_navbar(); ?>
    <br>

    <form action="" method="post" autocomplete="off" enctype="multipart/form-data"
        style="padding: 20px; text-align: center;">
        <h1 style="margin-top: -20px; color: red;">Change Image?</h1>
        <br>
        <img src="/no_track/images/wardrobe_images/<?php echo $prechange_image; ?>" alt="" width="300px">
        <br><br>

        <div class="file-input-wrapper">
            <input id="file_input_change_image" type="file" name="PHOTO" accept=".jpg, .jpeg, .png" required>
            <label for="file_input_change_image" class="custom-file-label">Choose NEW Image</label>
        </div>
        <div id="image_preview"></div>

        <script>
            document.getElementById('file_input_change_image').addEventListener('change', function (event) {
                const fileInput = event.target;
                const file = fileInput.files[0];
                const previewContainer = document.getElementById('image_preview');
                const fileName = fileInput.files[0].name;

                // Clear previous image
                previewContainer.innerHTML = '';

                if (file) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.maxWidth = '100%'; // Adjust as needed
                    img.style.maxHeight = '300px'; // Adjust as needed
                    previewContainer.appendChild(img);
                    document.getElementById('add_button').style.display = 'inline-block';
                    document.getElementById('add_button').value = 'Change Image to \'' + fileName + '\' ';
                }
            });
        </script>

        <input type="hidden" name="original_photo" value="<?php echo $prechange_image; ?>">
        <input id="add_button" type="submit" name="add_button" value="Upload NEW Image"
            style="display: none; margin-top: 5%;">
    </form>

</body>

</html>