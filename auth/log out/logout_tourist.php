<?php
session_start();
header('Content-Type: application/json');
require_once '../../config.php'; 

session_unset();
session_destroy();

echo json_encode(['success' => true, 'message' => 'Tourist logged out']);
?>