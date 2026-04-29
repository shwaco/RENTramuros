<?php
include_once('../../../asset/config.php');
/** @var mysqli $con */

$result = mysqli_query($con, "SELECT * FROM Attractions");
?>

<h2>Tourist Attractions</h2>

<a href="post_attractions.php">Add Attraction</a>

<table border="2" cellpadding="10" cellspacing="0">

<tr>
<th>ID</th>
<th>Type</th>
<th>NAME</th>
<th>Description</th>
<th>Schedule</th>
<th>Fee</th>
<th>Main img</th>
<th>Mini img 1</th>
<th>Mini img 2</th>
<th>Rec img</th>
<th>Action</th>
</tr>

<?php

while($row = mysqli_fetch_assoc($result)) {

echo "<tr>"; 

echo "<td>".$row['attraction_id']."</td>";
echo "<td>".$row['attraction_type']."</td>";
echo "<td>".$row['attraction_name']."</td>";
echo "<td>".$row['description']."</td>";
echo "<td>".$row['schedule']."</td>";
echo "<td>".$row['fee']."</td>";
echo "<td><img src='../../../asset/img/".$row['main_img']."' width='100px'></td>";
echo "<td><img src='../../../asset/img/".$row['mini_one_img']."' width='100px'></td>";
echo "<td><img src='../../../asset/img/".$row['mini_two_img']."' width='100px'></td>";
echo "<td><img src='../../../asset/img/".$row['rec_img']."' width='100px'></td>";

echo "<td>
<a href='patch_attractions.php?id=".$row['attraction_id']."'>Edit</a> |
<a href='delete_attractions.php?id=".$row['attraction_id']."'>Delete</a> 
</td>";

echo "</tr>";

}

?>

</table>