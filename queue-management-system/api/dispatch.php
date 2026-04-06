<?php
function runDispatch($conn) {
    // para makuha yung oldest waiting na tourist
    $sqlT = "SELECT customer_id FROM tourists WHERE status = 'waiting' ORDER BY created_at ASC LIMIT 1";
    $resultT = mysqli_query($conn, $sqlT);
    $tourist = mysqli_fetch_assoc($resultT);

    // ito naman para sa tour guide na oldest waiting din
    $sqlG = "SELECT guide_id FROM tour_guides WHERE current_status = 'Available' ORDER BY became_available_at ASC LIMIT 1";
    $resultG = mysqli_query($conn, $sqlG);
    $guide = mysqli_fetch_assoc($resultG);

    // then kapag pareho silang nag eexist, imamatch sila nito
    if ($tourist && $guide) {
        $tid = $tourist['customer_id'];
        $gid = $guide['guide_id'];

        // inaaupdate yung tourist
        $updateTouristSql = "UPDATE tourists SET status = 'serving', called_at = NOW() WHERE customer_id = ?";
        $stmtT = mysqli_prepare($conn, $updateTouristSql);
        mysqli_stmt_bind_param($stmtT, "i", $tid);
        mysqli_stmt_execute($stmtT);

        // inaaupdate yung tour guide tas nililink dun sa tourist and minamark siya as busy
        $updateGuideSql = "UPDATE tour_guides SET current_status = 'Busy', current_tourist_id = ? WHERE guide_id = ?";
        $stmtG = mysqli_prepare($conn, $updateGuideSql);
        mysqli_stmt_bind_param($stmtG, "ii", $tid, $gid);
        mysqli_stmt_execute($stmtG);
        
        return true;
    }
    return false;
}
?>