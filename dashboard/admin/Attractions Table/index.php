<?php
include_once('../connect_phpmyadmin.php');

$result = mysqli_query($conn, "SELECT * FROM Attractions");
?>

<h2>Tourist Attractions</h2>

<a href="create.php">Add Attraction</a>

<table border="1">

<tr>
<th>ID</th>
<th>NAME</th>
<th>Entrace Fee</th>
<th>Operating Hours</th>
<th>Action</th>
</tr>

<?php

while($row = mysqli_fetch_assoc($result)) {

echo "<tr>"; 

echo "<td>".$row['attraction_id']."</td>";
echo "<td>".$row['attraction_name']."</td>";
echo "<td>".$row['entrance_fee']."</td>";
echo "<td>".$row['operating_hours']."</td>";

echo "<td>
<a href='edit.php?id=".$rowo['attraction_id']."'>Edit</a> |
<a href='delete.php?id=".$rowo['attraction_id']."'>Delete</a> 
</td>";

echo "</tr>";

}

?>

</table>