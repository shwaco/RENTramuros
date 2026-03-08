<?php
session_start();
session_destroy();
header('location: login_system_user.php');
?>