<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$HOSTNAME = getenv('RENTRAMUROS_DB_HOST') ?: 'localhost';
$USERNAME = getenv('RENTRAMUROS_DB_USER') ?: 'fill_username';
$PASSWORD = getenv('RENTRAMUROS_DB_PASS') ?: 'fill_password';
$DATABASE_NAME = getenv('RENTRAMUROS_DB_NAME') ?: 'rentramuros_db';

// #region agent log (debug-mode)
error_log('[01db3a config] attempting mysqli_connect to DB ' . $DATABASE_NAME . ' as ' . $USERNAME . '@' . $HOSTNAME);
// #endregion agent log (debug-mode)

mysqli_report(MYSQLI_REPORT_OFF);
$con = @mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE_NAME);

if (!$con) {
    header('Content-Type: application/json');
    http_response_code(500);
    // #region agent log (debug-mode)
    error_log('[01db3a config] mysqli_connect failed: ' . mysqli_connect_error());
    // #endregion agent log (debug-mode)
    echo json_encode(['success' => false, 'message' => 'Database Connection Failed: ' . mysqli_connect_error()]);
    exit;
}

mysqli_set_charset($con, "utf8mb4");
?>