<?php
function getGroupedAttractions($con, $booking_request_id) {
   $fetch_sql = "SELECT attraction_id FROM request_attractions WHERE booking_request_id = ?";
   $fetch_stmt = mysqli_prepare($con, $fetch_sql);
   mysqli_stmt_bind_param($fetch_stmt, "i", $booking_request_id);

    if(mysqli_stmt_execute($fetch_stmt)) {
        $result = mysqli_stmt_get_result($fetch_stmt);
        $attractions = [];

        while ($row = mysqli_fetch_assoc($result)) {
            array_push($attractions, $row['attraction_id']);
        }
        return $attractions;
    }
    return [];
}
?>