<?php
include_once('../connect_phpmyadmin.php');

$id = $_GET['id'];

$query = "DELETE FROM Attractions WHERE attraction_id=$id";

mysqli_query($conn,$query);

header("Location: index.php");

?>