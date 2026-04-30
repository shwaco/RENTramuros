<?php
session_start();
require_once '../config/config.php';

if (!isset($_SESSION['guide_id'])) {
    header("Location: ../auth.v2/login.php");
    exit();
}

$guide_id = $_SESSION['guide_id'];


// SELECT query para kunin yung guide info — kinukuha ang first_name, current_status, at became_available_at
$stmtInfo = mysqli_prepare($con, "SELECT first_name, current_status, became_available_at FROM tour_guides WHERE guide_id = ?");
mysqli_stmt_bind_param($stmtInfo, "i", $guide_id);
mysqli_stmt_execute($stmtInfo);
$guideInfo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtInfo));

// Guard: if guide record not found, session is stale — redirect to login
if (!$guideInfo) {
    session_unset();
    session_destroy();
    header("Location: ../auth.v2/login.php");
    exit();
}

$guideName     = $guideInfo['first_name'];
$currentStatus = $guideInfo['current_status'];
$isAssigned    = false;
$tourData      = null;

// If guide is On Tour, fetch the current Accepted booking with all related data
// conditional SELECT — JOIN query lang ang pinapatakbo kung 'On Tour' ang status
// para maiwasan ang unnecessary DB queries para sa mga guide na hindi pa naka-tour
if ($currentStatus === 'On Tour') {
    $query = "SELECT 
                bh.booking_request_id,
                bh.booking_date,
                bh.adults_and_seniors,
                bh.children,
                bh.infants,
                bh.number_of_vehicle,
                v.vehicle_type,
                ci.first_name,
                ci.last_name,
                ci.email_address,
                ci.phone_number,
                p.package_name,
                p.price                                                     AS package_price,
                GROUP_CONCAT(
                    CONCAT(a.attraction_name, '|', IFNULL(a.fee, 0))
                    ORDER BY a.attraction_name
                    SEPARATOR ','
                )                                                   AS destinations
              FROM booking_history bh
              LEFT JOIN contact_information ci  ON bh.contact_info_id   = ci.contact_info_id
              LEFT JOIN vehicles v              ON bh.vehicle_id         = v.vehicle_id
              LEFT JOIN packages p              ON bh.package_id         = p.package_id
              LEFT JOIN request_attractions ra  ON bh.booking_request_id = ra.booking_request_id
              LEFT JOIN attractions a           ON ra.attraction_id       = a.attraction_id
              WHERE bh.guide_id = ?
                AND bh.status = 'Accepted'
              GROUP BY bh.booking_request_id
              LIMIT 1";

   $stmtTour = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmtTour, "i", $guide_id);
    mysqli_stmt_execute($stmtTour);
    $tourData = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtTour));

    if ($tourData && !empty($tourData['booking_request_id'])) {
        $isAssigned = true;
    } else {
        // After matapos yung tour or macancel, ibabalik yung guide sa queuing status para makakuha ng bagong tour
        $resetSql = "UPDATE tour_guides SET current_status = 'Queuing', current_tourist_id = NULL, became_available_at = NOW() WHERE guide_id = ?";
        $resetStmt = mysqli_prepare($con, $resetSql);
        mysqli_stmt_bind_param($resetStmt, "i", $guide_id);
        mysqli_stmt_execute($resetStmt);

        // Override the variable so the page immediately loads the Queue view below
        $currentStatus = 'Queuing'; 
    }
}

// Queue position calculation
// COUNT query para ma-compute ang queue position ng guide
// ang became_available_at ang ginagamit bilang sort key; mas maaga = mas mataas sa queue
$queuePosition = 0;
if ($currentStatus === 'Queuing') {
    $stmtP = mysqli_prepare($con,
        "SELECT COUNT(*) + 1 as pos FROM tour_guides 
         WHERE current_status = 'Queuing' 
           AND (became_available_at < ? OR (became_available_at = ? AND guide_id < ?))");
    mysqli_stmt_bind_param($stmtP, "ssi",
        $guideInfo['became_available_at'],
        $guideInfo['became_available_at'],
        $guide_id);
    mysqli_stmt_execute($stmtP);
    $result        = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtP));
    $queuePosition = ($result && isset($result['pos'])) ? (int)$result['pos'] : 1;
}
?>

<?php require_once 'php/head.php'; ?>
<body class="minimal-theme">

<?php require_once 'php/nav.php'; ?>

<main style="padding-bottom: 10rem;">
    <section class="content-wrapper" id="active-tour-view" style="padding-top: 0.5rem; display: block;" aria-label="Dashboard">

        <?php if ($isAssigned): ?>
            <?php require_once 'php/view_on_tour.php'; ?>

        <?php elseif ($currentStatus === 'Online' || $currentStatus === 'Clocked In' || $currentStatus === 'Offline' || $currentStatus === 'Available'): ?>
            <?php require_once 'php/view_clockin.php'; ?>

        <?php elseif ($currentStatus === 'Queuing'): ?>
            <?php require_once 'php/view_queue.php'; ?>

        <?php endif; ?>
    </section>

    <?php require_once 'php/view_history.php'; ?>
</main>

<?php require_once 'php/footer.php'; ?>
<?php require_once 'php/modals.php'; ?>
<?php require_once 'php/scripts.php'; ?>

</body>
</html>