<?php
session_start();
require_once '../config/config.php';

// tiga-check kung may active session ang guide, kung wala i-redirect sa login
if (!isset($_SESSION['guide_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

$guide_id = $_SESSION['guide_id'];

// kunin ang guide information including name at current status
$stmtInfo = mysqli_prepare($con, "SELECT first_name, current_status, became_available_at FROM tour_guides WHERE guide_id = ?");
mysqli_stmt_bind_param($stmtInfo, "i", $guide_id);
mysqli_stmt_execute($stmtInfo);
$guideInfo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtInfo));

$guideName     = $guideInfo['first_name'];
$currentStatus = $guideInfo['current_status'];
$isAssigned    = false;
$tourData      = null;

// kung On Tour ang guide, kunin ang details ng current tourist na naka-assign
if ($currentStatus === 'On Tour') {
    $query = "SELECT t.first_name, t.last_name, t.email, t.phone_number, t.customer_id,
              t.service_type AS vehicle_type, p.package_name,
              COALESCE(
                  NULLIF(GROUP_CONCAT(CONCAT(a.attraction_name, '|', IFNULL(a.entrance_fee, 0)) SEPARATOR ','), ''),
                  t.destinations
              ) as destinations,
              t.created_at, t.called_at, t.adult_count, t.children_count, t.infant_count, t.vehicle_count
              FROM tour_guides tg
              LEFT JOIN tourists t ON tg.current_tourist_id = t.customer_id
              LEFT JOIN package_bookings pb ON tg.guide_id = pb.guide_id AND pb.tour_date = CURDATE()
              LEFT JOIN packages p ON pb.package_id = p.package_id
              LEFT JOIN package_itinerary pi ON p.package_id = pi.package_id
              LEFT JOIN attractions a ON pi.attraction_id = a.attraction_id
              WHERE tg.guide_id = ? GROUP BY tg.guide_id";

    $stmtTour = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmtTour, "i", $guide_id);
    mysqli_stmt_execute($stmtTour);
    $tourData = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtTour));

    if ($tourData && !empty($tourData['customer_id'])) {
        $isAssigned = true;
    }
}

// kung Queuing ang guide, kalkulahin ang posisyon niya sa queue gamit ang
// became_available_at timestamp + guide_id bilang tiebreaker
$queuePosition = 0;
if ($currentStatus === 'Queuing') {
    $stmtP = mysqli_prepare($con, "SELECT COUNT(*) + 1 as pos FROM tour_guides WHERE current_status = 'Queuing' AND (became_available_at < ? OR (became_available_at = ? AND guide_id < ?))");
    mysqli_stmt_bind_param($stmtP, "ssi", $guideInfo['became_available_at'], $guideInfo['became_available_at'], $guide_id);
    mysqli_stmt_execute($stmtP);
    $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtP));
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

        <?php elseif ($currentStatus === 'Online' || $currentStatus === 'Clocked In'): ?>
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
