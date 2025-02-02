<?php
session_start();

require($_SERVER['DOCUMENT_ROOT'] ."/berkemrepaker/navbar.php");
check_login_and_validate_access("wardrobe");
require('../../../no_track/db_connections/wardrobe_connection.php');


// Get the referring page ///////////////////////////////////////
if (isset($_SERVER['HTTP_REFERER'])) {
  $referrer = $_SERVER['HTTP_REFERER'];
  $parsedUrl = parse_url($referrer);
  
  if (isset($parsedUrl['path'])) {
      $path = $parsedUrl['path'];
      // echo "You came from: " . htmlspecialchars($path);
  } else {
      // echo "Path information not available.";
      $path = '';
  }
} else {
  // echo "No referrer information available.";
  $path='';
}
////////////////////////////////////////////////////////////////

if ($_SERVER['REQUEST_METHOD'] != 'POST' && $path != '/berkemrepaker/wardrobe/searchbar/search_fe.php')
{
  header("Location: choose_table.php");
  exit();
}
else if (isset($_POST['table_choice']))
{
  $_SESSION['table_choice'] = $_POST['table_choice'];
}
else if (isset($_POST['multi_select'])) 
{
  $selected_products = $_POST['multi_select'];
  // print_r($selected_products);

  foreach ($selected_products as $item) 
  {
    // print_r($item);
    // echo "<br>";
    // SQL query to copy data from 'main' to 'istanbul' where id is 21
    $sql = "INSERT INTO ".$_SESSION['table_choice']." (ID, COLOUR, PHOTO, LOCATION, INFO, DATE_MODIFIED)
    SELECT ID, COLOUR, PHOTO, LOCATION, INFO, DATE_MODIFIED
    FROM main
    WHERE id = $item";
  
    // Execute the query
    if ($wardrobe_con->query($sql) === TRUE) 
    {
      echo "<br>";
    } 
    else 
    {
      echo "Error: " . $sql . "<br>" . $wardrobe_con->error;
    }
  }
  header("Location: search_fe.php");
  exit();
} 
else if (isset($_POST['multi_delete'])) 
{
  $items_to_delete = $_POST['multi_delete'];
  // print_r($items_to_delete);
  // Check connection
  if ($wardrobe_con->connect_error) {
    die("Connection failed: " . $wardrobe_con->connect_error);
  }

  // Ensure IDs are integers to prevent SQL injection
  $items_to_delete = array_map('intval', $items_to_delete);

  // Construct the SQL query
  $sql = "DELETE FROM ".$_SESSION['table_choice']." WHERE id IN (" . implode(',', $items_to_delete) . ")";

  // Execute the query
  if (!($wardrobe_con->query($sql) === TRUE))
  {
    echo "Error deleting records: " . $wardrobe_con->error;
  }
}

?>
  <title>Live Search</title>

  <script src="/berkemrepaker/JSscripts/bootstrap.min.js"></script>
  <script src="/berkemrepaker/JSscripts/popper.min.js"></script>
  <script src="/berkemrepaker/JSscripts/jquery.min.js"></script>
  
  <?php navbar_head_style(); ?>
  <link rel="stylesheet" href="../w_style.css">
  <style>
    body {
      text-align: center;
    }
    .parent_div {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      width: 100%;
    }

    .child_div {
      margin: 1%;
    }
  </style>
  
</head>
<body>
  <?php navbar_body(); ?>
    <?php wardrobe_navbar();  ?>
    <br>
  <input type="text" id="search">
  <br><br>
  <div class="parent_div">
    <div class="child_div">
      <h2 style="color: white;">Add From Main Database</h2>
      <form action="" method="post">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>ADD</th>
              <th id="ID" >ID</th>
              <th id="Colour">Colour</th>
              <th id="Photo">Photo</th>
              <th id="Location">Location</th>
              <th id="Info">Info</th>
              <th id="Date_Uploaded">Date Uploaded</th>
            </tr>
          </thead>
          <tbody id="output">
          </tbody>
        </table>
        <br>
        <input class="fixed_button" name="selection_submit" type="submit" value="SAVE SELECTION">
      </form>
    </div>
    <div class="child_div">
      <h2 style="color: white;">Delete From '<?php echo $_SESSION['table_choice'];?>'</h2>
      <form action="" method="post">
        <table  class="table table-hover">
          <tr>
            <th>ADD</th>
              <th id="ID" >ID</th>
              <th id="Colour">Colour</th>
              <th id="Photo">Photo</th>
              <th id="Location">Location</th>
              <th id="Info">Info</th>
              <th id="Date_Uploaded">Date Uploaded</th>
          </tr>
          <?php
          $sql = "SELECT * FROM ".$_SESSION['table_choice'];
          $result = $wardrobe_con-> query($sql);
          if ($result-> num_rows > 0) 
          {
              while ($row = $result-> fetch_assoc())  
              {        
                echo "<tr>
                        <td> <input type=\"checkbox\" name=\"multi_delete[]\" id=\"". $row['ID'] ."\" value=\"". $row['ID'] ."\" > </td>
                        <td>". $row['ID'] ."</td>
                        <td>". $row['COLOUR'] ."</td>
                        
                        <td class=\"photo_class\">". "<img style='width: 100%; min-width: 5px; max-width: 100px;' src='/no_track/images/wardrobe_images/". $row["PHOTO"] ."' class=\"photo_img\" title=\"title\"> </td>
                        <td>". $row['LOCATION'] ."</td>
                        <td>". $row['INFO'] ."</td>
                        <td>". $row['DATE_MODIFIED'] ."</td>
                    </tr>";
                }           
          }
          else 
          {
              echo "0 results";
          }
          
          
          ?>
        </table>
        <br>
        <input class="fixed_button" name="delete_submit" type="submit" value="DELETE SELECTION">
      </form>
    </div>
  </div>
  <!-- now this is updating everytime you write so issue with sp-products fixed with this version -->
  <script type="text/javascript">
    $(document).ready(function(){
            // Define the function to send the AJAX request
            function sendAjaxRequest() {
                $.ajax({
                    type: 'GET',
                    url: 'search_be.php',
                    data: {
                        name: $("#search").val(),
                        table_name_of_choice: "<?php echo $_SESSION['table_choice']; ?>"
                    },
                    success: function(data){
                        $("#output").html(data);
                    }
                });
            }

            // Send the AJAX request when the page initially loads
            sendAjaxRequest();

            // Send the AJAX request when the input field changes
            $("#search").on('input', function(){
                sendAjaxRequest();
            });
        });
  </script>

</body>
</html>
