<?php
session_start();

$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['role'] === 'tourist';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Flex:opsz,wght,XOPQ,XTRA,YOPQ,YTDE,YTFI,YTLC,YTUC@8..144,100..1000,96,468,79,-203,738,514,712&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">



    <link rel="stylesheet" href="navsidebar.css?v=<?php echo filemtime('navsidebar.css'); ?>">
    <link rel="stylesheet" href="main.css?v=<?php echo filemtime('main.css'); ?>">
    <link rel="stylesheet" href="index.css?v=<?php echo filemtime('index.css'); ?>">
    <link rel="stylesheet" href="../reusable_bookings_and_receipt/styles.css?v=<?php echo filemtime('../reusable_bookings_and_receipt/styles.css'); ?>">
    <script src="navsidebar.js?v=<?php echo filemtime('navsidebar.js'); ?>" defer></script>
    <script type="module" src="dynamic_landing.js?v=<?php echo filemtime('dynamic_landing.js'); ?>" defer></script>
    <script>
        window.IS_LOGGED_IN = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
    </script>

    <style>
        /* Receipt modal overlay */
        #tourist-receipt-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.55);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        #tourist-receipt-content {
            background: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            min-height: 85vh;
            max-height: 95vh;
            overflow-y: auto;
        }
        /* Pending status badge */
        .status-pending {
            color: #d97706;
            font-weight: 400;
        }
    </style>
    

</head>
<body>
    
    <header>
        <!-- Navigation bar-->
       <nav>

            <ul class="sidebar">
                <li onclick=hideSidebar() id="hideSidebar"><a href="#" ><img src="../../asset/img/close_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="close-button" width="auto" height="30"></a></li>
                <li><a href="#"><img src="../../asset/img/map_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="map" width="auto" height="20">Map</a></li>
                
                <?php if ($isLoggedIn): ?>
                    <li><a href="../tour_page/tours_latest.php"><img src="../../asset/img/tour_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="tours" width="auto" height="20">Tours</a></li>
                    <li><a href="#" onclick="openMyBookings(); hideSidebar(); return false;"><img src="../../asset/img/book_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="book" width="auto" height="20">My Bookings</a></li>
                <?php else: ?>
                    <li><a href="../login_page/login.php"><img src="../../asset/img/tour_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="tours" width="auto" height="20">Tours</a></li>
                    <li><a href="../login_page/login.php"><img src="../../asset/img/book_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="book" width="auto" height="20">My Bookings</a></li>
                <?php endif; ?>
                
                <li><a href="#">About</a></li>
            </ul>

            <ul class="navbar">
                <li><img src="../../asset/img/RENTRAMUROS_LOGO_BLACK_1920X775.svg" alt="RENTRAMUROS_LOGO" width="auto" height="67" id="logo" onclick="closeMyBookings()" style="cursor:pointer;"></li>
                <li class="hideOnMobile"><a href="#interactive-map" id="maps"><img src="../../asset/img/map_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="map" width="auto" height="20">Map</a></li>
                
                <?php if ($isLoggedIn): ?>
                    <li class="hideOnMobile"><a href="../tour_page/tours_latest.php"><img src="../../asset/img/tour_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="tours" width="auto" height="20">Tours</a></li>
                    <li class="hideOnMobile"><a href="#" onclick="openMyBookings(); return false;"><img src="../../asset/img/book_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="book" width="auto" height="20">My Bookings</a></li>
                    <li class="hideOnMobile"><a href="#">About</a></li>
                    <li class="hideOnMobile last"><a href="../../backend/mailer/api/logout_api.php" style="color: #8D230F; font-weight: bold;">Logout</a></li>
                <?php else: ?>
                    <li class="hideOnMobile"><a href="../login_page/login.php"><img src="../../asset/img/tour_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="tours" width="auto" height="20">Tours</a></li>
                    <li class="hideOnMobile"><a href="../login_page/login.php"><img src="../../asset/img/book_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="book" width="auto" height="20">My Bookings</a></li>
                    <li class="hideOnMobile"><a href="#">About</a></li>
                    <li class="hideOnMobile last"><a href="../login_page/login.php" style="font-weight: bold;">Login</a></li>
                <?php endif; ?>

                <li onclick="showSidebar()" id="showSidebar" class="menu-btn"><a href="#" ><img src="../../asset/img/menu_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="menu-button" width="auto" height="25" ></a></li>
            </ul>
        </nav>

    </header>

    <main>
        <!-- Landing page content — hidden when My Bookings is active -->
        <div id="landing-content">

        <!-- Hero section -->
        <section class="hero">

            <!-- Decorative lines -->
            <div class="lines">

                <div class="batch one">
                    <span class="line one"></span>
                    <span class="line two"></span>
                </div>

                <div class="wheel_pic"><img src="../../asset/img/CARTWHEEL_ICON.svg" alt="wheel_pic" id="cartwheel" height="20"></div>

                <div class="batch two">
                    <span class="line one"></span>
                    <span class="line two"></span>
                </div>

            </div>

            <div class="content">
                <!-- Content -->
                <h1>HOP ON A JOURNEY ACROSS <br>THE CITY WITHIN WALLS WITH <span class="span rent">RENT</span><span class="span ramuros">RAMUROS</span></h1>

                <p>Where history, culture, and seamless booking intertwine. Experience an effortless <br>and prepared journey for using the all-in-one platform for your Intramuros travel and tour needs.</p>

                <ul class="list_container">
                    <li><img src="../../asset/img/CARTWHEEL_ICON.svg" height="15">Navigate easily</li>
                    <li><img src="../../asset/img/CARTWHEEL_ICON.svg" height="15">Travel with few clicks</li>
                    <li><img src="../../asset/img/CARTWHEEL_ICON.svg" height="15">Hassle-free</li>
                </ul>

                <?php if (!$isLoggedIn): ?>
                    <a href="../sign_up_page/sign_up.php" class="button">START YOUR JOURNEY</a>
                <?php endif; ?>
            </div>
        </section>

        <!-- Mid section -->
        <section class="mid">            

            <!-- Upcoming events -->
            <div class="upcoming_container">

                <div class="title_wrapper">
                    <div class="title">
                        <div class="upcoming"><p>Upcoming Events</p></div>
                        <div class="v_calendar"><a href="." rel="noopener noreferrer">(View Calendar)</a></div>    
                    </div>

                    <div class="feedback"><a href="." rel="noopener noreferrer">Feedback</a></div>
                </div>

                <!-- Sliding -->
                <div class="slider">
                    
                    <button class="slide-btn one" id="prev-btn"><img src="../../asset/img/chevron_backward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="back-arrow"></button>

                    <ul id="upcoming_events_list">
                    </ul>

                    <button class="slide-btn two" id="next-btn"><img src="../../asset/img/chevron_forward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="forward-arrow"></button>

                </div>
            </div>

            <!-- Popular attractions -->
            <div class="slider-container one">

                <div class="heading one">
                Popular Attractions 
                </div>

                <div class="slider one">
                        
                    <button class="slide-btn one" id="prev-btn1"><img src="../../asset/img/chevron_backward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="back-arrow"></button>

                    <ul id="pop-attractions-list"></ul>

                    <button class="slide-btn two" id="next-btn1"><img src="../../asset/img/chevron_forward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="forward-arrow"></button>
                </div>

            </div>


            <!-- Recommended attractions -->
            <div class="slider-container two">

                <div class="heading two">Recommended Attractions</div>

                <div class="slider two">
                        
                    <button class="slide-btn one" id="prev-btn2"><img src="../../asset/img/chevron_backward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="back-arrow"></button>

                    <ul id="reco-attractions-list">
                    </ul>

                    <button class="slide-btn two" id="next-btn2"><img src="../../asset/img/chevron_forward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="forward-arrow"></button>


                </div>
            </div>

            <!-- Packages -->
            <section class="package_wrapper">
                <div class="heading three">Packages u cannot miss</div>

                <ul class="packages" id="package_list">
                </ul>
            </section>            
            
        </section>
        
        <section id="interactive-map" class="map-section" style="width: 85%; margin: 2rem auto 5rem auto; border-radius: 20px; overflow: hidden;">
            <div style="text-align: center; font-family: 'roboto flex'; font-size: 1.5rem; font-weight: bold; margin-bottom: 1.5rem;">
                Intramuros Interactive Map
            </div>
            
            <div style="position: relative; height: 850px; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <?php include '../../shared/components/map/index.php'; ?>
            </div>
        </section>

        </div><!-- end #landing-content -->

        <!-- My Bookings view — shown when nav link is clicked, hidden by default -->
        <section id="my-bookings-view" style="display: none; padding: 4rem 2rem;">
            <div style="width: 100%; max-width: 800px; margin: 0 auto;">
                <header style="margin-bottom: 2rem; text-align: left;">
                    <h2 style="font-size: 2.5rem; font-weight: 900; margin: 0; color: #000; font-family: 'Roboto', sans-serif; letter-spacing: -1px;">My Bookings</h2>
                    <hr style="border: 0; border-bottom: 1px solid #000; margin-top: 1rem;">
                </header>
                <div id="historyContainer" class="history-cards-container" style="display: flex; flex-direction: column; gap: 1rem;">
                </div>
            </div>
        </section>

    </main>

    <!-- Receipt modal overlay -->
    <div id="tourist-receipt-overlay">
        <div id="tourist-receipt-content"></div>
    </div>

    <!-- Receipt template -->
    <template id="receipt-modal-template">
        <div class="rcpt-header-container">
            <div class="rcpt-id-badge">{{id}}</div>
            <button onclick="closeReceipt()" class="rcpt-close-btn">&times;</button>
        </div>
        <div class="rcpt-date-text">{{formattedDate}}</div>
        <div class="rcpt-section-title">TOURIST</div>
        <div class="rcpt-grid-3">
            <span class="rcpt-label">ADULTS & SENIORS</span>
            <span class="rcpt-subtext">(18 years old and above)</span>
            <span class="rcpt-value">{{adults_and_seniors}}</span>
        </div>
        <div class="rcpt-grid-3">
            <span class="rcpt-label">CHILDREN</span>
            <span class="rcpt-subtext">(2 to 17 years old)</span>
            <span class="rcpt-value">{{children}}</span>
        </div>
        <div class="rcpt-grid-3 last">
            <span class="rcpt-label">INFANTS</span>
            <span class="rcpt-subtext">(under 2 years old)</span>
            <span class="rcpt-value">{{infants}}</span>
        </div>
        <div class="rcpt-flex-between">
            <span class="rcpt-bold-label">PACKAGE</span>
            <span class="rcpt-font-condensed">{{packageDisplayString}}</span>
        </div>
        <hr class="rcpt-divider-dashed">
        <div class="rcpt-section-title">ITINERARY</div>
        <div class="rcpt-itinerary-grid">{{destinationsHTML}}</div>
        <div class="rcpt-grid-3">
            <span class="rcpt-bold-label">VEHICLE</span>
            <span class="rcpt-uppercase rcpt-center">{{vehicleDisplayString}}</span>
            <span class="rcpt-font-condensed rcpt-bold-value">{{number_of_vehicle}}</span>
        </div>
        <hr class="rcpt-divider-dashed">
        <div class="rcpt-section-title">CONTACT INFORMATION</div>
        <div class="rcpt-flex-between-sm">
            <span class="rcpt-font-condensed">FULL NAME:</span>
            <span class="rcpt-uppercase">{{first_name}} {{last_name}}</span>
        </div>
        <div class="rcpt-flex-between-sm">
            <span class="rcpt-font-condensed">EMAIL ADDRESS:</span>
            <span class="rcpt-font-condensed">{{email_address}}</span>
        </div>
        <div class="rcpt-flex-between-md">
            <span class="rcpt-font-condensed">PHONE NUMBER:</span>
            <span class="rcpt-font-condensed">{{phone_number}}</span>
        </div>
        <hr class="rcpt-divider-solid">
        <div class="rcpt-totals-container">
            <div class="rcpt-totals-grid">
                <span class="rcpt-total-label">TOTAL FEE:</span>
                <span class="rcpt-total-val">₱{{baseStr}}</span>
                <span class="rcpt-total-label">TOUR GUIDE FEE:</span>
                <span class="rcpt-total-val">₱1,000 - ₱1,500</span>
                <span class="rcpt-grand-label">GRAND TOTAL:</span>
                <span class="rcpt-grand-val">₱{{minGrandStr}} - ₱{{maxGrandStr}}</span>
            </div>
            {{actionArea}}
        </div>
    </template>

    <!-- Scripts -->
    <script>
        // Same view-switching pattern as the tour guide index.php
        function openMyBookings() {
            document.getElementById('landing-content').style.display = 'none';
            document.getElementById('my-bookings-view').style.display = 'block';
            window.scrollTo(0, 0);
        }
        function closeMyBookings() {
            document.getElementById('my-bookings-view').style.display = 'none';
            document.getElementById('landing-content').style.display = 'block';
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeReceipt();
        });
    </script>

</body>
</html>