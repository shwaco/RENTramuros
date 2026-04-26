<?php 
// simulan ang session at i-require ang database config
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

$guideName = $guideInfo['first_name'];
$currentStatus = $guideInfo['current_status'];
$isAssigned = false;
$tourData = null;

// kung Busy ang guide, kunin ang details ng current tourist na naka-assign
if ($currentStatus === 'Busy') {
    // FIX #8: removed t.total_amount — that column does not exist in the tourists table
    // FIX #12: COALESCE falls back to t.destinations for walk-in tourists who have no
    //          package/attractions join — their itinerary was previously always blank
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

    // tiga-check kung may assigned tourist talaga
    if ($tourData && !empty($tourData['customer_id'])) {
        $isAssigned = true;
    }
}

$queuePosition = 0;
// kung Available ang guide, tiga-calculate ng position nito sa queue
if ($currentStatus === 'Available') {
    // tiga-count kung ilan ang guides na nangunguna sa queue (based sa became_available_at timestamp)
    $stmtP = mysqli_prepare($con, "SELECT COUNT(*) + 1 as pos FROM tour_guides WHERE current_status = 'Available' AND (became_available_at < ? OR (became_available_at = ? AND guide_id < ?))");
    mysqli_stmt_bind_param($stmtP, "ssi", $guideInfo['became_available_at'], $guideInfo['became_available_at'], $guide_id);
    mysqli_stmt_execute($stmtP);
    $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtP));
    $queuePosition = ($result && isset($result['pos'])) ? (int)$result['pos'] : 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RENTramuros - Guide Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400&family=Roboto+Serif:wght@400;500;700&family=Roboto:wght@400;700;900&display=swap" rel="stylesheet">
</head>
<body class="minimal-theme">

    <header>
        <nav class="top-nav" aria-label="Main Navigation">
           <div class="brand-container">
                <a href="#" onclick="switchView('dashboard'); return false;" style="display:flex; align-items:center; text-decoration:none; color:inherit;">
                    <img src="../img/RENTRAMUROS_LOGO_WHITE_1920X775 (1).svg" alt="RENTramuros" class="nav-logo-img">
                </a>
            </div>
            
            <div class="nav-menu">
                <a href="#" onclick="switchView('history'); return false;" class="nav-link">Tour history</a>
                <a href="#" class="nav-link">About</a>
                
                <div class="profile-dropdown-container">
                    <button class="profile-btn" id="profileBtn" aria-haspopup="menu" aria-expanded="false">
                        <div class="user-avatar"><i class="fas fa-user"></i></div>
                    </button>
                    
                    <div class="profile-dropdown-menu" id="profileMenu" role="menu">
                        <div class="dropdown-header">
                            <div class="dropdown-avatar"><i class="fas fa-user"></i></div>
                            <div class="dropdown-user-info">
                                <strong><?php echo htmlspecialchars($guideName); ?></strong>
                                <span>Tour Guide</span>
                            </div>
                        </div>
                        <hr class="dropdown-divider">
                        
                        <?php if ($currentStatus !== 'Busy'): ?>
                            <button onclick="handleClockOut()" class="dropdown-item" role="menuitem">
                                <i class="fas fa-power-off"></i> Clock Out
                            </button>
                            <hr class="dropdown-divider">
                        <?php endif; ?>
                        <button onclick="handleLogoutOnly()" class="dropdown-item text-danger" role="menuitem">
                            <i class="fas fa-sign-out-alt"></i> Sign Out
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main style="padding-bottom: 10rem;">
        <section class="content-wrapper" id="active-tour-view" style="padding-top: 0.5rem; display: block;" aria-label="Dashboard">
            
            <?php if ($isAssigned): ?>
                <div class="queue-layout-wrapper">
                    <header style="display: flex; justify-content: flex-start; align-items: center; width: 100%; margin-top: -0.5rem; margin-bottom: 1rem;">
                        <h2 class="queue-status-header" style="margin: 0; font-family: 'Roboto', sans-serif; font-weight: 700;">
                            STATUS: <span style="color: #dc2626; font-family: 'Roboto Serif', serif; font-weight: 400;">On tour</span>
                        </h2>
                    </header>

                    <article style="background: #ffffff; padding: 0 2rem 2.5rem 2rem; border-radius: 4px; width: 100%; max-width: 500px; border: 1px solid #e5e7eb; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin: 0 auto; display: flex; flex-direction: column;">
                        
                        <div style="display: flex; justify-content: center; align-items: center; margin: 0 -2rem; padding: 0.5rem 0.5rem 0.5rem 0.5rem; border-bottom: 1px solid #e5e7eb;">
                        <div style="background-color: #000000; color: #ffffff; font-family: 'Roboto Condensed', sans-serif; font-size: 1.4rem; font-weight: 700; padding: 0.4rem 1.2rem; border-radius: 4px; display: inline-flex; justify-content: center; align-items: center; line-height: 1;">
                                <?php echo $tourData['customer_id']; ?>
                            </div>
                        </div>
                        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 0 -2rem 1.5rem -2rem;">
                        <div style="text-align: right; font-size: 0.8rem; color: #000000; margin-bottom: 2rem; font-family: 'Roboto Condensed', sans-serif; font-weight: 700;">
                            <?php echo date('F j, Y ; h:i A', strtotime($tourData['created_at'])); ?>
                        </div>

                        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; color:#000; font-family: 'Roboto Condensed', sans-serif;">TOURIST</div>
                        
                        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem;">
                            <span style="font-weight: 400; padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ADULTS & SENIORS</span>
                            <span style="font-weight: 200; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(18 years old and above)</span>
                            <span style="font-weight: 300; text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo $tourData['adult_count'] ?: 0; ?></span>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem;">
                            <span style="font-weight: 400; padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CHILDREN</span>
                            <span style="font-weight: 200; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(2 to 17 years old)</span>
                            <span style="font-weight: 300; text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo $tourData['children_count'] ?: 0; ?></span>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:1.5rem; font-size:0.85rem;">
                            <span style="font-weight: 400; padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">INFANTS</span>
                            <span style="font-weight: 200; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(under 2 years old)</span>
                            <span style="font-weight: 300; text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo $tourData['infant_count'] ?: 0; ?></span>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.85rem;">
                            <span style="font-weight:700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">PACKAGE</span>
                            <span style="font-weight: 300;font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo $tourData['package_name'] ?: 'NO'; ?></span>
                        </div>

                        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

                        <div style="font-weight: 700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ITINERARY</div>
                        <div style="font-weight: 300; padding-left: 0.25rem; display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; font-size: 0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin-bottom: 1.5rem;">
                         <?php 
                                $destinations = $tourData['destinations'] ? explode(',', $tourData['destinations']) : ['No itineraries listed'];
                                
                                foreach($destinations as $dest) {
                                    if (trim($dest) === 'No itineraries listed') {
                                        echo '<span>' . htmlspecialchars(trim($dest)) . '</span>';
                                        continue;
                                    }
                                    
                                    // Split the string into Name and Fee using the '|' delimiter
                                    $parts = explode('|', $dest);
                                    $name = trim($parts[0]);
                                    $fee = isset($parts[1]) ? (int)$parts[1] : 0;
                                    
                                    // If the fee is greater than 0, show the green price!
                                    if ($fee > 0) {
                                        echo '<span>' . htmlspecialchars($name) . '&nbsp;&nbsp;<span style="color: #16a34a; font-weight: 600; font-style: italic; font-size: 0.8rem;">₱' . $fee . '</span></span>';
                                    } else {
                                        // If the fee is 0 (or null), just show the name
                                        echo '<span>' . htmlspecialchars($name) . '</span>';
                                    }
                                }
                            ?>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; font-size: 0.85rem;">
                            <span style="font-weight: 700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">VEHICLE</span>
                            <span style="font-weight: 300;font-family: 'Roboto Condensed', sans-serif; color: #000000; text-transform: uppercase; text-align: center;"><?php echo $tourData['vehicle_type'] ?: 'NONE'; ?></span>
                            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000; text-align: right;"><?php echo $tourData['vehicle_count'] ?: 0; ?></span>
                        </div>

                        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

                        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CONTACT INFORMATION</div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
                            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000;">FULL NAME:</span>
                            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo htmlspecialchars($tourData['first_name'] . ' ' . $tourData['last_name']); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
                            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000;">EMAIL ADDRESS:</span>
                            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo htmlspecialchars($tourData['email']); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.85rem;">
                            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000;">PHONE NUMBER:</span>
                            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000;"><?php echo htmlspecialchars($tourData['phone_number']); ?></span>
                        </div>

                        <div style="display: flex; gap: 0.75rem; align-items: center;">
                            <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000; font-size: 0.9rem;">TOTAL FEE:</span>
                            <span style="font-weight: 400; font-family: 'Roboto Condensed', sans-serif; color: #109620; font-size: 0.9rem; font-style: italic;">₱4,500-5000</span>
                        </div>

                    </article>
                </div> 

            <?php elseif ($currentStatus === 'Idle' || $currentStatus === 'Offline'): ?>
                <section class="tourist-selection-container" aria-label="Tourist Selection Area" style="margin-top: 3rem;">
                    <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; width: 100%; margin-bottom: 1.5rem;">
                        <i class="fas fa-user-friends" style="font-size: 1.25rem; color: #000;"></i>
                        <h3 style="margin: 0; font-size: 1.2rem; font-weight: 900; color: #000; letter-spacing: -0.5px; font-family: 'Roboto Condensed', sans-serif;">
                            <?php echo ($queuePosition === 1) ? 'Select a Tourist' : 'Waiting Tourists (View Only)'; ?>
                        </h3>
                    </div>
                    <div id="tourist-lobby" class="tourist-blocks-container" aria-label="Waiting Tourists"></div>
                </section>

                <article class="minimal-state-wrapper">
                  
                    <div style="display: flex; gap: 1rem; justify-content: center; align-items: center;">
                        <?php if ($currentStatus === 'Offline'): ?>
                            <button onclick="clockIn()" class="btn-minimal-primary" style="background-color: #000000; border-color: #000000; color: #ffffff;">Clock In</button>
                            <button class="btn-minimal-primary" style="opacity: 0.4; cursor: not-allowed;" disabled>Join Queue</button>
                        <?php else: ?>
                            <button class="btn-minimal-primary" style="background-color: #109620; border-color: #109620; color: #ffffff; opacity: 0.8; cursor: default;" disabled>
                                <i class="fas fa-check"></i> Clocked In
                            </button>
                            <button onclick="joinQueue()" class="btn-minimal-primary">Join Queue</button>
                        <?php endif; ?>
                    </div>
                </article>

            <?php elseif ($currentStatus === 'Available'): ?>
                <article class="queue-layout-wrapper">
                  <header style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-top: -0.5rem; margin-bottom: 0.5rem;">
                        <h2 class="queue-status-header" style="margin: 0; font-family: 'Roboto', sans-serif; font-weight: 900;">
                            STATUS: <span class="text-green" style="font-family: 'Roboto Serif', serif; font-weight: 400;">Queuing #<?php echo $queuePosition; ?></span>
                        </h2>
                        
                        <?php if ($currentStatus === 'Available' && $queuePosition === 1): ?>
                            <div style="background-color: #fee2e2; color: #dc2626; padding: 0.5rem 1.25rem; border-radius: 50px; font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; gap: 8px; border: 1px solid #f87171; font-family: 'Roboto', sans-serif; white-space: nowrap; flex-shrink: 0;" aria-live="polite">
                                <i class="far fa-clock"></i>
                                <span>Select Tourist: <span style="font-family: 'Roboto Serif', serif;"><span id="selection-timer">15</span>s</span></span>
                            </div>
                        <?php endif; ?>
                    </header>

                    <section class="tourist-selection-container" aria-label="Tourist Selection Area" style="margin-top: 1rem;">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; width: 100%; margin-bottom: 1.5rem;">
                            <i class="fas fa-user-friends" style="font-size: 1.25rem; color: #000;"></i>
                            <h3 style="margin: 0; font-size: 1.2rem; font-weight: 900; color: #000; letter-spacing: -0.5px; font-family: 'Roboto Condensed', sans-serif;">
                                <?php echo ($queuePosition === 1) ? 'Select a Tourist' : 'Waiting Tourists (View Only)'; ?>
                            </h3>
                        </div>
                        <div id="tourist-lobby" class="tourist-blocks-container" aria-label="Waiting Tourists"></div>
                    </section>
                    
                    <section class="welcome-card-container">
                        <div style="background-color: #ffffff; width: 9rem; height: 9rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 2.5rem; border: 1px solid #e5e7eb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <i class="fas fa-user" style="font-size: 4rem; color: #9ca3af;"></i> 
                        </div>
                        <h2 style="font-size: 3rem; font-weight: 900; color: #000; margin: 0; letter-spacing: -1px; font-family: 'Roboto Condensed', sans-serif;">
                            Welcome, <?php echo htmlspecialchars($guideName); ?>!
                        </h2>
                    </section>
                </article>
            <?php endif; ?>
        </section>

       <section class="content-wrapper" id="history-view" style="display: none; align-items: flex-start; padding: 4rem 2rem;" aria-label="Tour History">
            <div style="width: 100%; max-width: 1100px; margin: 0 auto;">
                <header style="margin-bottom: 2rem; text-align: left;">
                    <h2 style="font-size: 2.5rem; font-weight: 900; margin: 0; color: #000; font-family: 'Roboto', sans-serif; letter-spacing: -1px;">Tour History</h2>
                </header>
                <div class="table-wrap">
                    <table class="history-table-minimal">
                        <thead>
                            <tr>
                                <th scope="col">Tourist Name</th>
                                <th scope="col">Date Completed</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id="historyTable"></tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

   <footer class="bottom-footer">
        <div class="footer-layout">
            <section class="footer-branding">
                <div class="branding-details">
                    <div class="footer-logo-wrapper">
                        <img src="../img/RENTRAMUROS_LOGO_WHITE_1920X775 (1).svg" alt="RENTramuros" class="footer-logo-img">
                    </div>
                    <nav class="social-icons" aria-label="Social Media">
                        <i class="fab fa-facebook" aria-hidden="true"></i>
                        <i class="fab fa-twitter" aria-hidden="true"></i>
                        <i class="fab fa-instagram" aria-hidden="true"></i>
                        <i class="far fa-envelope" aria-hidden="true"></i>
                    </nav>
                </div>
            </section>

            <nav class="footer-links" aria-label="Footer Navigation">
                <div class="link-column">
                    <h4>CONTACT</h4>
                    <a href="#">infos</a>
                </div>
                <div class="link-column">
                    <h4>ABOUT</h4>
                    <a href="#">infos</a>
                </div>
                <div class="link-column">
                    <h4>SUPPORT</h4>
                    <a href="#">infos</a>
                </div>
            </nav>
        </div>
        <div class="footer-copyright">
            <small>All right reserved. Copyright &copy; 2026 Rentramuros Manila.</small>
        </div>
    </footer>

    <div id="tourist-receipt-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
        <div id="tourist-receipt-content" style="background: #ffffff; padding: 0 2rem 2.5rem 2rem; border-radius: 4px; width: 95%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto; display: flex; flex-direction: column;">
        </div>
    </div>

    <div id="dynamic-confirm-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4); z-index: 10000; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
        <div style="background: #ffffff; border-radius: 8px; width: 90%; max-width: 420px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden; display: flex; flex-direction: column; font-family: 'Roboto', sans-serif;">
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.2rem 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <h3 id="dynamic-modal-title" style="margin: 0; font-size: 1.4rem; font-weight: 700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">Title</h3>
                <button onclick="closeDynamicModal()" style="background: none; border: none; font-size: 1.8rem; cursor: pointer; color: #000000; padding: 0; line-height: 1;">&times;</button>
            </div>
            
            <div style="padding: 1.5rem; color: #374151; font-size: 1rem; line-height: 1.5; font-weight: 300;">
                <p id="dynamic-modal-msg" style="margin: 0;">Message</p>
            </div>
            
            <div style="padding: 1rem 1.5rem; background-color: #e2e4e9; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button onclick="closeDynamicModal()" style="background: #ffffff; border: none; padding: 0.6rem 1.2rem; border-radius: 4px; font-weight: 700; font-size: 1rem; cursor: pointer; color: #000;">
                    Cancel
                </button>
                <button id="dynamic-modal-btn" style="border: none; padding: 0.6rem 1.2rem; border-radius: 4px; font-weight: 700; font-size: 1rem; cursor: pointer; color: #ffffff;">
                    Confirm
                </button>
            </div>
        </div>
    </div>

   <script>
        const IS_QUEUE_NUMBER_ONE = <?php echo ($currentStatus === 'Available' && $queuePosition === 1) ? 'true' : 'false'; ?>;
        const CURRENT_GUIDE_STATUS = "<?php echo $currentStatus; ?>";
        const queuePosition = <?php echo $queuePosition; ?>;
    </script>
    <script src="js/main.js"></script>
</body>
</html>