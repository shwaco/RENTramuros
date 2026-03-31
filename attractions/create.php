<?php
include "db.php";

if(isset($_POST['submit'])){

$name = $_POST['attraction_name'];
$fee = $_POST['entrance_fee'];
$hours = $_POST['operating_hours'];

$query = "INSERT INTO Attractions(attraction_name, entrance_fee, VALUES('$name','$fee','$hours')";

mysqli_query($conn,$query);

header("Location: index.php");

}

?>

<h2>Add Attraction</h2>

<form method="POST">

Name:
<input type="text" name="attraction_name" required><br><br>

Entrance Fee:
<input type="number" step="0.01" name="entrance_fee" required><br><br>

Operating Hours:
<input type="text" name="operating_hours"><br><br>

<button type="submit" name="submit">Add</button>

</form>
