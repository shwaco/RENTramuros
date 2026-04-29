<?php
session_start();
session_destroy();
header('location: auth/log in/login_tour_guide.php');
?>