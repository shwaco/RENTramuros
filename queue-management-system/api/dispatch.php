<?php
function runDispatch($conn) {
    // para makuha yung oldest waiting na tourist
    $stmtT = $conn->query("SELECT customer_id FROM tourists WHERE status = 'waiting' ORDER BY created_at ASC LIMIT 1");
    $tourist = $stmtT->fetch(PDO::FETCH_ASSOC);

    // ito naman para sa tour guide na oldest waiting din
    $stmtG = $conn->query("SELECT guide_id FROM tour_guides WHERE current_status = 'Available' ORDER BY became_available_at ASC LIMIT 1");
    $guide = $stmtG->fetch(PDO::FETCH_ASSOC);

    // then kapag pareho silang nag eexist, imamatch sila nito
    if ($tourist && $guide) {
        $tid = $tourist['customer_id'];
        $gid = $guide['guide_id'];

        // inaaupdate yung tourist
        $conn->prepare("UPDATE tourists SET status = 'serving', called_at = NOW() WHERE customer_id = ?")->execute([$tid]);

        // inaaupdate yung tour guide tas nililink dun sa tourist and minamark siya as busy
        $conn->prepare("UPDATE tour_guides SET current_status = 'Busy', current_tourist_id = ? WHERE guide_id = ?")->execute([$tid, $gid]);
        
        return true;
    }
    return false;
}
?>