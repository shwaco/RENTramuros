<?php
session_start();
header('Content-Type: application/json');

session_unset();
session_destroy();

// Send success response without ever touching the database queue!
echo json_encode(['success' => true, 'message' => 'Tour guide logged out safely without losing queue position']);
?>