<?php 
require_once 'config.php';
session_start();

if (!isset($_SESSION['guide_id'])) {
    header("Location: auth/log in/login_tour_guide.php");
    exit(); 
}

$guide_id = $_SESSION['guide_id'];  
$db = new Database();
$conn = $db->getConnection();

// taga kuha ng info ng guide
$stmtInfo = $conn->prepare("SELECT first_name, current_status, became_available_at FROM tour_guides WHERE guide_id = ?");
$stmtInfo->execute([$guide_id]);
$guideInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);

$guideName = $guideInfo['first_name']; // Safely store the name!
$currentStatus = $guideInfo['current_status'];

// taga fetch ng assigned tour
$isAssigned = false;
$tourData = null;

if ($currentStatus === 'Busy') {
    $query = "SELECT t.first_name, t.last_name, t.email, t.phone_number, t.customer_id,
              t.service_type AS vehicle_type, p.package_name,
              GROUP_CONCAT(a.attraction_name SEPARATOR ', ') as destinations,
              t.called_at
              FROM tour_guides tg
              LEFT JOIN tourists t ON tg.current_tourist_id = t.customer_id
              LEFT JOIN package_bookings pb ON tg.guide_id = pb.guide_id AND pb.tour_date = CURDATE()
              LEFT JOIN packages p ON pb.package_id = p.package_id
              LEFT JOIN package_itinerary pi ON p.package_id = pi.package_id
              LEFT JOIN attractions a ON pi.attraction_id = a.attraction_id
              WHERE tg.guide_id = ? GROUP BY tg.guide_id";

    $stmtTour = $conn->prepare($query);
    $stmtTour->execute([$guide_id]);
    $tourData = $stmtTour->fetch(PDO::FETCH_ASSOC);

    if ($tourData && !empty($tourData['customer_id'])) {
        $isAssigned = true;
    }
}

// ito yung queue position
$queuePosition = 0;
if ($currentStatus === 'Available') {
    $stmtP = $conn->prepare("
        SELECT COUNT(*) + 1 as pos 
        FROM tour_guides 
        WHERE current_status = 'Available' 
        AND became_available_at < ?
    ");
    $stmtP->execute([$guideInfo['became_available_at']]);
    $result = $stmtP->fetch(PDO::FETCH_ASSOC);
    $queuePosition = ($result && isset($result['pos'])) ? (int)$result['pos'] : 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RENTramuros | Guide Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <h1 class="nav-title"><i class="fas fa-map-marked-alt mr-2"></i> Tourist Guide Dashboard</h1>
                <button onclick="location.href='api/clock_out.php'" class="btn-clockout">
                    <i class="fas fa-sign-out-alt mr-2"></i> Clock Out
                </button>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="dashboard-card border-top">
            <h2 class="section-header">Assigned Tour</h2>
            
            <?php if ($isAssigned): ?>
                <div class="tour-grid" id="active-tour-card">
                    <div>
                        <div class="info-group">
                            <p class="info-label">Time and Date Assigned</p>
                            <p class="info-val-lg"><?php echo $tourData['called_at'] ? date('F j, Y - g:i A', strtotime($tourData['called_at'])) : 'Just now'; ?></p>
                        </div>
                        <div class="info-group">
                            <p class="info-label">Tourist Info</p>
                            <p class="info-val-xl"><?php echo htmlspecialchars($tourData['first_name'] . ' ' . $tourData['last_name']); ?></p>
                            <p class="info-subtext"><?php echo htmlspecialchars($tourData['email']); ?> <br> <?php echo htmlspecialchars($tourData['phone_number']); ?></p>
                        </div>
                    </div>
                    
                    <div class="highlight-box">
                        <div class="info-group">
                            <p class="info-label">Vehicle Type</p>
                            <p class="info-val-lg"><?php echo htmlspecialchars($tourData['vehicle_type'] ?? 'Standard Kalesa'); ?></p>
                        </div>
                        <div class="info-group">
                            <p class="info-label">Booking Type</p>
                            <span class="<?php echo !empty($tourData['package_name']) ? 'badge-purple' : 'badge-gray'; ?>">
                                <?php echo !empty($tourData['package_name']) ? "Package: " . htmlspecialchars($tourData['package_name']) : "Individual Booking"; ?>
                            </span>
                        </div>
                        <div class="info-group">
                            <p class="info-label">Destinations</p>
                            <p class="info-subtext"><?php echo htmlspecialchars($tourData['destinations'] ?: "City-wide Heritage Tour"); ?></p>
                        </div>
                    </div>
                </div>
                
                <button onclick="finishTour(<?php echo $tourData['customer_id']; ?>)" class="btn-complete">
                    <i class="fas fa-check-circle mr-2"></i> COMPLETE TOUR
                </button>
            <?php else: ?>
                <div class="idle-state">
                    <div class="idle-icon-wrap"><i class="fas fa-hourglass-half"></i></div>
                    <h2 class="welcome-text">Welcome, <?php echo htmlspecialchars($guideName); ?>!</h2>
                    <h3 class="idle-title">You are #<?php echo $queuePosition; ?> in line</h3>
                </div>
            <?php endif; ?>
        </div>

        <div class="dashboard-card">
            <h2 class="section-header"><i class="fas fa-history icon-history"></i> Your Tour History</h2>
            <div class="table-wrap">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Tourist Name</th>
                            <th>Date Completed</th>
                            <th>Vehicle</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="historyTable">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>
</html>