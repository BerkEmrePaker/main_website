<?php
session_start();

require("../../no_track/db_connections/todolist_connection.php");
require($_SERVER['DOCUMENT_ROOT'] ."/berkemrepaker/navbar.php");

check_login_and_validate_access("todolist");


if (isset($_GET['content'])) 
{
    $content = $_GET['content'];
    $sql = "INSERT INTO `todolist` (`Content`, `USER_ID`) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $content, $_SESSION['userID']);
    $stmt->execute();
    $stmt->close();
    header("Location: /berkemrepaker/todolist/todolist.php");
    exit();
}

if (isset($_GET['delete']) && isset($_GET['edit'])) 
{
    die("An error has been made, a request to delete and to edit a row was made at the same time!");
}

if (isset($_GET['delete']) && isset($_GET['select_id'])) 
{
    $sql = "SELECT * from todolist WHERE ITEM_ID = ". $_GET['select_id'] ." AND USER_ID = " . $_SESSION['userID'];
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $newSQL = "INSERT INTO `deleted_items` (`ITEM_ID`, `DATE`, `CONTENT`, `USER_ID`) VALUES (?, ?, ?, ?) ";
            $stmt = $con->prepare($newSQL);
            $stmt->bind_param("issi", $row['ITEM_ID'], $row['DATE'], $row['CONTENT'], $row['USER_ID']);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        echo "0 results";
    }
    $result->free_result();
    
    $sql = "DELETE from todolist WHERE ITEM_ID = ? AND USER_ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $_GET['select_id'], $_SESSION['userID']);
    $stmt->execute();
    $stmt->close();
}

if (isset($_GET['edit']) && isset($_GET['select_id'])) 
{
    $row_to_edit_id = $_GET['select_id'];
    $sql = "SELECT * FROM todolist WHERE ITEM_ID = ? AND USER_ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $row_to_edit_id, $_SESSION['userID']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) 
    {
        $row = $result->fetch_assoc();
        $content = $row["CONTENT"];
    } 
    else 
    {
        echo "No rows found.";
    }

    echo '
    <form action="" method="post">
        <span>Edit and save:</span>
        <input type="text" name="editted_text" autocomplete="off" value="' . $content . '">
        <br><br>
        <input type="hidden" name="select_id" value="' . $row_to_edit_id . '">
        <input type="submit" value="Save Edit">
    </form>
    ';
}

if (isset($_POST['editted_text'])) {
    $editted_text = $_POST['editted_text'];
    $select_id = $_POST['select_id'];
    $sql = "UPDATE todolist SET `CONTENT` = ? WHERE ITEM_ID = ? AND USER_ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sii", $editted_text, $select_id, $_SESSION['userID']);
    $stmt->execute();
    $stmt->close();
    // header("Location: /berkemrepaker/todolist/todolist.php"); // need to change to js 
    echo '  <script>
            document.location.href = "/berkemrepaker/todolist/todolist.php";
            </script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php navbar_head_style(); ?>
    
    <link rel="stylesheet" href="/berkemrepaker/CSSstyles/todolist.css?v=<?php echo time(); ?>">
    <title>To do list</title>
</head>
<body>
    <?php navbar_body(); ?>
    <form action="" method="get">
        <input type="text" name="content" id="content_id" autocomplete="off" placeholder="Type new stuff to do">
        <br><br>
        <input type="submit" value="Save">
    </form>
    

    <form action="" method="get">
        <table>
            <tr>
                <th>SELECT</th>
                <th>DATE</th>
                <th>CONTENT</th>
            </tr>
            <?php
            $sql = "SELECT * from todolist WHERE USER_ID = " . $_SESSION['userID'];
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td><input required type=\"radio\" name=\"select_id\" id=\"" . $row['ITEM_ID'] . "\" value=\"" . $row['ITEM_ID'] . "\" ></td>
                        <td>" . $row['DATE'] . "</td>
                        <td>" . $row['CONTENT'] . "</td>
                    </tr>";
                }
            } else {
                echo "0 results";
            }
            $result->free_result();
            $con->close();
            ?>
        </table>

        <input type="submit" value="Delete Row" name="delete" onclick="return confirm('Are you sure you want to delete this row? This action is irreversible!')">
        <input type="submit" value="Edit Row" name="edit">
    </form>
</body>
</html>
