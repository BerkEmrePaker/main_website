<?php
require('../../../no_track/db_connections/wardrobe_connection.php');


// Create connection
$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$sql = "SELECT *
FROM main
WHERE CONCAT_WS(' ', ID, COLOUR, LOCATION, INFO, DATE_MODIFIED)    
LIKE '%".$_GET['name']."%'
order by DATE_MODIFIED DESC;
"; // must search for rows not already in the table


/////////////////////////////////////////////////////////////////////////////
$sql2 = "SELECT *
  FROM ".$_GET['table_name_of_choice']."
  WHERE CONCAT_WS(' ', ID, COLOUR, PHOTO, LOCATION, INFO, DATE_MODIFIED);";
  // must search for rows not already in the table

  $result2 = $wardrobe_con-> query($sql2);
//   print_r($result2);
  
  if ($result2-> num_rows > 0) 
  {
    while ($row = $result2-> fetch_assoc())  
    {                   
      $mylist[] = $row["ID"];
    }  
  }
  else 
  {
    $mylist[] = [];
  }
//   echo "<br><br>";
//   print_r($mylist);
/////////////////////////////////////////////////////////////////////////////


// print_r($sql);
$result = $con-> query($sql);
if ($result-> num_rows > 0) 
{
    while ($row = $result-> fetch_assoc())  
    {        
        if (!in_array($row['ID'], $mylist)) 
        {
            echo "<tr>
                    <td> <input type=\"checkbox\" name=\"multi_select[]\" id=\"". $row['ID'] ."\" value=\"". $row['ID'] ."\" > </td>
                    <td>". $row['ID'] ."</td>
                    <td>". $row['COLOUR'] ."</td>
                    
                    <td class=\"photo_class\">". "<img style='width: 100%; min-width: 5px; max-width: 100px;' src='/no_track/images/wardrobe_images/". $row["PHOTO"] ."' class=\"photo_img\" title=\"title\"> </td>
                    <td>". $row['LOCATION'] ."</td>
                    <td>". $row['INFO'] ."</td>
                    <td>". $row['DATE_MODIFIED'] ."</td>
                </tr>";
        }  
        }           
}
else 
{
    echo "0 results";
}


echo "<br>";

?>