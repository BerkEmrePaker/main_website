<?php
// Require connection to MySQL
require("../no_track/db_connections/main_connection.php");

function getUserInfoResult($username_input, $conn)
{
    $query = "SELECT ID, USERNAME, PASSWORD, STATUS FROM users WHERE USERNAME = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt === false) {
        error_log("Preparation failed: " . mysqli_error($conn));
        die("An error occurred. Please try again later.");
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "s", $username_input);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    if ($result === false) {
        error_log("Query failed: " . mysqli_error($conn));
        die("An error occurred. Please try again later.");
    }

    return $result;
}

if (isset($_POST['login_type'])) {
    if ($_POST['login_type'] == "user" && isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize username input
        $username_input = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
        $password_input = $_POST['password'];

        $result = getUserInfoResult($username_input, $conn);

        // Collect data from database
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $userID = $row['ID'];
            $username = $row['USERNAME'];
            $hashed_password = $row['PASSWORD'];
            $status = $row['STATUS'];

            // Verify password
            if (password_verify($password_input, $hashed_password)) {
                // Start secure session
                session_start();
                session_regenerate_id(true);

                // Set session variables
                $_SESSION['username'] = $username_input;
                $_SESSION['userID'] = $userID;
                $_SESSION['access'] = explode(";", $status);

                // Redirect to intended page or home page
                if (isset($_SESSION['initial_visited_page'])) {
                    header("Location: " . $_SESSION['initial_visited_page']);
                } else {
                    header("Location: index.php");
                }
                die();
            } else {
                // Invalid username or password
                header("Location: login.php?error=invalid_credentials");
                die();
            }
        } else {
            // User not found
            header("Location: login.php?error=user_not_found");
            die();
        }
    } elseif ($_POST['login_type'] == "guest") {
        // Redirect guest to guest page
        header("Location: /berkemrepaker/guest/guest.html");
        die();
    } else {
        // Access denied
        header("Location: access_denied.php");
        die();
    }
}
?>