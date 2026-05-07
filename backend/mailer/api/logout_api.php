<?php
session_start();
session_unset();
session_destroy();

// Imbes na mag-echo ng JSON, ire-redirect natin siya pabalik sa landing page
header("Location: ../../../pages/login_page/login.php");
exit();
?>