<?php
session_start();

require($_SERVER['DOCUMENT_ROOT'] . "/berkemrepaker/navbar.php");
require('../../no_track/db_connections/wardrobe_connection.php');

check_login_and_validate_access("wardrobe");

if (isset($_POST['add_button'])) {
    handleImageUploadAndDatabaseInsertion($_POST, $_FILES, $wardrobe_con);
}

header("Location: display_wardrobe.php");
die();

/**
 * Handles the entire image upload and database insertion process.
 *
 * @param array $postData The POST data from the form.
 * @param array $fileData The FILES data containing the uploaded image.
 * @param mysqli $dbConnection The database connection object.
 */
function handleImageUploadAndDatabaseInsertion($postData, $fileData, $dbConnection) {
    $colour = $postData['COLOUR'];
    $location = $postData['LOCATION'];
    $info = $postData['INFO'];

    if ($fileData['PHOTO']['error'] == 4) {
        alert("Image does not exist");
        return;
    }

    $newImageName = processImage($fileData['PHOTO']);
    if (!$newImageName) {
        return;
    }

    insertIntoDatabase($dbConnection, $colour, $newImageName, $location, $info);
}

/**
 * Processes and resizes the uploaded image, then saves it to the server.
 *
 * @param array $photoFile The uploaded file data.
 * @return string|false The new image name if successful, false otherwise.
 */
function processImage($photoFile) {
    $validImageExtensions = ['jpg', 'jpeg', 'png'];
    $fileName = $photoFile['name'];
    $tmpName = $photoFile['tmp_name'];
    
    $fileInfo = pathinfo($fileName);
    $imageExtension = strtolower($fileInfo['extension']);

    if (!in_array($imageExtension, $validImageExtensions)) {
        alert("Invalid Image Extension");
        return false;
    }

    $srcImage = createImageResource($tmpName, $imageExtension);
    if (!$srcImage) {
        alert("Unsupported image format");
        return false;
    }

    list($width, $height) = getimagesize($tmpName);
    $newWidth = 300;
    $factor = $width / $newWidth;
    $newHeight = $height / $factor;

    $dstImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    $newImageName = uniqid() . '.' . $imageExtension;
    $destination = $_SERVER['DOCUMENT_ROOT'] . "/no_track/images/wardrobe_images/" . $newImageName;

    saveImageResource($dstImage, $destination, $imageExtension);

    imagedestroy($srcImage);
    imagedestroy($dstImage);

    return $newImageName;
}

/**
 * Creates an image resource from an uploaded file.
 *
 * @param string $filePath The file path of the uploaded image.
 * @param string $extension The image file extension.
 * @return resource|false The image resource or false on failure.
 */
function createImageResource($filePath, $extension) {
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            return imagecreatefromjpeg($filePath);
        case 'png':
            return imagecreatefrompng($filePath);
        default:
            return false;
    }
}

/**
 * Saves an image resource to a given destination.
 *
 * @param resource $imageResource The image resource.
 * @param string $destination The file path to save the image.
 * @param string $extension The image file extension.
 */
function saveImageResource($imageResource, $destination, $extension) {
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($imageResource, $destination);
            break;
        case 'png':
            imagepng($imageResource, $destination);
            break;
    }
}

/**
 * Inserts the given data into the database.
 *
 * @param mysqli $dbConnection The database connection object.
 * @param string $colour The colour value.
 * @param string $photoName The name of the uploaded photo.
 * @param string $location The location value.
 * @param string $info Additional info.
 */
function insertIntoDatabase($dbConnection, $colour, $photoName, $location, $info) {
    $stmt = $dbConnection->prepare("INSERT INTO main (COLOUR, PHOTO, LOCATION, INFO) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $colour, $photoName, $location, $info);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $dbConnection->close();
}

/**
 * Displays a JavaScript alert message.
 *
 * @param string $message The message to display.
 */
function alert($message) {
    echo "<script>alert('$message');</script>";
}