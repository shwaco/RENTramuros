<?php 
function requireRole($allowedRoles) {
    // check if you're logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) ){
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Access denied. Please log in first."
        ]);
        exit(); 
    };
    
    // check if you're authorized
    $currentUserRole = $_SESSION['role'];

    if (!in_array($currentUserRole, $allowedRoles)) {
        http_response_code(403);
        echo json_encode([
            "status" => "error",
            "message" => "Unauthorized. Your account type is not permitted."
        ]);
        exit();
    }
}

?>