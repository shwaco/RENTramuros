<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Flex:opsz,wght,XOPQ,XTRA,YOPQ,YTDE,YTFI,YTLC,YTUC@8..144,100..1000,96,468,79,-203,738,514,712&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />   
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>



    <link rel="stylesheet" href="asset/css/navsidebar.css?v=<?php echo filemtime('asset/css/navsidebar.css'); ?>">
    <link rel="stylesheet" href="DashWithMap/style.css?v=<?php echo filemtime('DashWithMap/style.css.css'); ?>">
    <link rel="stylesheet" href="asset/main.css?v=<?php echo filemtime('asset/main.css'); ?>">
    <link rel="stylesheet" href="asset/css/index.css?v=<?php echo filemtime('asset/css/index.css'); ?>">
    <script type="module" src="asset/navsidebar.js?v=<?php echo filemtime('asset/navsidebar.js'); ?>" defer></script>
    <script type="module" src="asset/js/dynamic_landing.js?v=<?php echo filemtime('asset/js/dynamic_landing.js'); ?>" defer></script>
    <script src="DashWithMap/main.js?v=<?php echo filemtime('DashWithMap/main.js'); ?>" defer></script>
    

</head>
<body>
    
    <header>
        <!-- Navigation bar-->
        <nav>

            <!-- Sidebar -->
            <ul class="sidebar">
                <li onclick=hideSidebar() id="hideSidebar"><a href="#" ><img src="asset/img/close_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="close-button" width="auto" height="30"></a></li>
                <li><a href="#"><img src="asset/img/map_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="map" width="auto" height="20">Map</a></li>
                <li><a href="#"><img src="asset/img/tour_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="tours" width="auto" height="20">Tours</a></li>
                <li><a href="#"><img src="asset/img/book_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="book" width="auto" height="20">My Bookings</a></li>
                <li><a href="#">About</a></li>
            </ul>

            <!-- Navigation bar -->
            <ul class="navbar">
                <li><img src="asset/img/RENTRAMUROS_LOGO_BLACK_1920X775.svg" alt="RENTRAMUROS_LOGO" width="auto" height="67" id="logo"></li>
                <li class="hideOnMobile"><a href="#" id="maps"><img src="asset/img/map_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="map" width="auto" height="20">Map</a></li>
                <li class="hideOnMobile"><a href="dashboard/tourist/tours.php" rel="noreferrer noopener" target="_blank"><img src="asset/img/tour_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="tours" width="auto" height="20">Tours</a></li>
                <li class="hideOnMobile"><a href="#"><img src="asset/img/book_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="book" width="auto" height="20">My Bookings</a></li>
                <li class="hideOnMobile last"><a href="#">About</a></li>
                <!-- <li><a href="auth.v2/login.php" id="nav_login"></li> -->
                <li onclick=showSidebar() id="showSidebar" class="menu-btn"><a href="#" ><img src="asset/img/menu_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="menu-button" width="auto" height="25" ></a></li>
            </ul>
        </nav>

    </header>

    <main>
        <!-- Hero section -->
        <section class="hero">

            <!-- Decorative lines -->
            <div class="lines">

                <div class="batch one">
                    <span class="line one"></span>
                    <span class="line two"></span>
                </div>

                <div class="wheel_pic"><img src="asset/img/CARTWHEEL_ICON.svg" alt="wheel_pic" id="cartwheel" height="20"></div>

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
                    <li><img src="asset/img/CARTWHEEL_ICON.svg" height="15">Navigate easily</li>
                    <li><img src="asset/img/CARTWHEEL_ICON.svg" height="15">Travel with few clicks</li>
                    <li><img src="asset/img/CARTWHEEL_ICON.svg" height="15">Hassle-free</li>
                </ul>

                <a href="auth.v2/log_in.php" class="button">START YOUR JOURNEY</a>
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
                    
                    <button class="slide-btn one" id="prev-btn"><img src="asset/img/chevron_backward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="back-arrow"></button>

                    <ul id="upcoming_events_list">
                    </ul>

                    <button class="slide-btn two" id="next-btn"><img src="asset/img/chevron_forward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="forward-arrow"></button>

                </div>
            </div>

            <!-- Popular attractions -->
            <div class="slider-container one">

                <div class="heading one">
                Popular Attractions 
                </div>

                <div class="slider one">
                        
                    <button class="slide-btn one" id="prev-btn1"><img src="asset/img/chevron_backward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="back-arrow"></button>

                    <ul id="pop-attractions-list"></ul>

                    <button class="slide-btn two" id="next-btn1"><img src="asset/img/chevron_forward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="forward-arrow"></button>
                </div>

            </div>


            <!-- Recommended attractions -->
            <div class="slider-container two">

                <div class="heading two">Recommended Attractions</div>

                <div class="slider two">
                        
                    <button class="slide-btn one" id="prev-btn2"><img src="asset/img/chevron_backward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="back-arrow"></button>

                    <ul id="reco-attractions-list">
                    </ul>

                    <button class="slide-btn two" id="next-btn2"><img src="asset/img/chevron_forward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="forward-arrow"></button>


                </div>
            </div>

            <!-- Packages -->
            <section class="package_wrapper">
                <div class="heading three">Packages u cannot miss</div>

                <ul class="packages" id="package_list">
                </ul>
            </section>            
            
        </section>


    </main>

    <section class="map_sec">
        <div id="search-container">
            <input list="intramuros-locations" id="search-input" placeholder="Search popular tourist spots...">
            
            <datalist id="intramuros-locations">
                <option value="Fort Santiago">
                <option value="Minor Basilica">
                <option value="San Agustin Church">
                <option value="Casa Manila">
                <option value="Baluarte de San Diego">
                <option value="Rizal Shrine">
                <option value="Palacio Del Gobernador">
                <option value="Museo De Intramuros">
                <option value="Silahi's Art And Artifacts Inc">
                <option value="Rizal's Bagumbayan Light and Sound Museum">
                <option value="Barbara's Heritage Restaurant">
                <option value="Bambike Ecotours">
                <option value="Puerta Del Parian">
            </datalist>
            
            <button id="search-btn">🔎Search</button>
        </div>

        <div id="map"></div>
        <div id="side-panel">
            <button id="close-panel-btn" onclick="document.getElementById('side-panel').classList.remove('open')">X</button>
            
            <img id="panel-img" src="" alt="Location Image">
            
            <div class="panel-content">
                <h2 id="panel-title">Title</h2>
                <p id="panel-text">Details dito</p>
                <h3 id="panel-operating-hours-header">🕰️Operating Hours:</h3>
                    <div id="panel-operating-hours-details"></div>
                </details>
                <a id="panel-btn" href="#" target="_blank" class="book-now-btn">
                Book Now</a>
            </div>
        </div>

        <div id="church-buttons">
            <button id="churches-btn" class="Churches-btn">⛪ Churches</button>
        </div>
        <div id="foodplaces-buttons">
            <button id="food-places-btn" class="Food-places-btn">🍽️ Food Places</button>
        </div>
        <div id="museums-buttons">
            <button id="museum-btn" class="Museum-btn">🖼️ Museums </button>
        </div>
        <div id="landmarks-buttons">
            <button id="landmark-btn" class="Landmark-btn">🏛️ Historical Landmarks </button>
        </div>
        <div id="rides-buttons">
            <button id="ride-btn" class="Rides-btn">🚲 Activities </button>
        </div>
        <div id="show-all-buttons">
            <button id="show-all-btn" class="Show-all-btn">📍 Show all pins</button>
        </div>

    </section>

</body>
</html>