<?php
include_once('../connect_phpmyadmin.php');  

$id = $_GET['id'];

$result = mysqli_query($conn,"SELECT * FROM Attractions WHERE attraction_id=$id");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){

$name = $_POST['attraction_name'];
$fee = $_POST['entrance_fee'];
$hours = $_POST['operating_hours'];

$query = "UPDATE Attractions 
          SET attraction_name='$name',
              entrance_fee='$fee',
              operating_hours='$hours'
          WHERE attraction_id=$id";

mysqli_query($conn,$query);

header("Location: index.php");

}
?>

<h2>Edit Attraction</h2>

<form method="POST">

Name:
<input type="text" name="attraction_name" value="<?php echo $row['attraction_name']; ?>"><br><br>

Entrance Fee:
<input type="number" step="0.01" name="entrance_fee" value="<?php echo $row['entrance_fee']; ?>"><br><br>

Operating Hours:
<input type="text" name="operating_hours" value="<?php echo $row['operating_hours']; ?>"><br><br>

<button type="submit" name="update">Update</button>

</form>