<?php
function generateRandomCode($length = 8) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $code;
}

function generateBookingCode($conn) {
    $prefix = "BR";
    do {
        $code = $prefix . "-" . generateRandomCode(8);
        $check = "SELECT unique_id FROM booking_history WHERE unique_id = '$code'";
        $result = mysqli_query($conn, $check);
    } while (mysqli_num_rows($result) > 0);
    return $code;
}
?>