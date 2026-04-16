<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Flex:opsz,wght,XOPQ,XTRA,YOPQ,YTDE,YTFI,YTLC,YTUC@8..144,100..1000,96,468,79,-203,738,514,712&family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../asset/main.css?v=<?php echo filemtime('../../asset/main.css'); ?>">
    <link rel="stylesheet" href="./index.css">

    <script src="../../asset/script.js?v=<?php echo filemtime('../../asset/script.js'); ?>" defer></script>

    <script src="../../services/api.js?v=<?php echo filemtime('../../services/api.js'); ?>" defer></script>

</head>
<body>
    
    <header>
        <!-- Navigation bar-->
        <nav>

            <!-- Sidebar -->
            <ul class="sidebar">
                <li onclick=hideSidebar()><a href="#"><img src="../../asset/img/close_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="close-button" width="auto" height="30"></a></li>
                <li><a href="#"><img src="../../asset/img/map_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="map" width="auto" height="20">Map</a></li>
                <li><a href="#"><img src="../../asset/img/tour_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="tours" width="auto" height="20">Tours</a></li>
                <li><a href="#"><img src="../../asset/img/book_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="book" width="auto" height="20">My Bookings</a></li>
                <li><a href="#">About</a></li>
            </ul>

            <!-- Navigation bar -->
            <ul class="navbar">
                <li><img src="../../asset/img/RENTRAMUROS_LOGO_BLACK_1920X775.svg" alt="RENTRAMUROS_LOGO" width="auto" height="67" id="logo"></li>

                <li class="hideOnMobile"><a href="#" id="maps"><img src="../../asset/img/map_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="map" width="auto" height="20">Map</a></li>
                <li class="hideOnMobile"><a href="#"><img src="../../asset/img/tour_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="tours" width="auto" height="20">Tours</a></li>
                <li class="hideOnMobile"><a href="#"><img src="../../asset/img/book_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="book" width="auto" height="20">My Bookings</a></li>
                <li class="hideOnMobile last"><a href="#">About</a></li>
                <li><button type="button">Log in</button></li>
                <li onclick=showSidebar() class="menu-btn"><a href="#"><img src="../../asset/img/menu_19dp_000000_FILL0_wght400_GRAD0_opsz20.svg" alt="menu-button" width="auto" height="25" ></a></li>
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

                <button type="button">START YOUR JOURNEY</button>
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
                <div class="slider_wrapper">
                    
                    <button class="btn one" id="prev-btn"><img src="../../asset/img/chevron_backward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="back-arrow"></button>

                    <div class="event_container">
                        <div class="image"><img src="../../asset/img/6154443154988404815.jpg" alt="ewan"></div>

                        <div class="details_container">
                            <div class="schedule_container">
                                <div class="frequency">January 29</div>
                                <div class="time">4PM - 12AM</div>
                            </div>
                            <div class="name">PASIG RIVER ESPLANADE BAZAAR</div>

                            <div class="loc_wrapper">
                                <img src="../../asset/img/location_icon.svg" alt="ewan">

                                <div class="loc">Pasig River Esplanade</div>
                            </div>
                        </div>
                    </div>

                    <div class="event_container">
                        <div class="image"><img src="../../asset/img/6154443154988404815.jpg" alt="ewan"></div>

                        <div class="details_container">
                            <div class="schedule_container">
                                <div class="frequency">Daily</div>
                                <div class="time">4PM - 12AM</div>
                            </div>
                            <div class="name">PASIG RIVER ESPLANADE BAZAAR</div>

                            <div class="loc_wrapper">
                                <img src="../../asset/img/location_icon.svg" alt="ewan">

                                <div class="loc">Pasig River Esplanade</div>
                            </div>
                        </div>
                    </div>

                    <div class="event_container">
                        <div class="image"><img src="../../asset/img/6154443154988404815.jpg" alt="ewan"></div>

                        <div class="details_container">
                            <div class="schedule_container">
                                <div class="frequency">Daily</div>
                                <div class="time">4PM - 12AM</div>
                            </div>
                            <div class="name">PASIG RIVER ESPLANADE BAZAAR</div>

                            <div class="loc_wrapper">
                                <img src="../../asset/img/location_icon.svg" alt="ewan">

                                <div class="loc">Pasig River Esplanade</div>
                            </div>
                        </div>
                    </div>

                    <button class="btn two" id="next-btn"><img src="../../asset/img/chevron_forward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="forward-arrow"></button>

                </div>
            </div>

            <!-- Popular attractions -->
            <div class="slider-container one">

                <div class="heading one">
                Popular Attractions 
                </div>

                <div class="slider one">
                        
                    <button class="slide-btn one" id="prev-btn1"><img src="../../asset/img/chevron_backward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="back-arrow"></button>

                    <ul id="popular-attractions-list"></ul>

                    <button class="slide-btn two" id="next-btn1"><img src="../../asset/img/chevron_forward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="forward-arrow"></button>
                </div>

            </div>


            <!-- Recommended attractions -->
            <div class="slider-container two">

                <div class="heading two">Recommended Attractions
                </div>

                <div class="slider two">
                        
                    <button class="slide-btn one" id="prev-btn2"><img src="../../asset/img/chevron_backward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="back-arrow"></button>

                    <ul>
                        
                        <li><a href="." rel="noopener noreferrer"><img src="../../asset/img/6154443154988404815.jpg" alt="ewan"><p>Casa la cote</p></a></li>
                        <li><a href="." rel="noopener noreferrer"><img src="../../asset/img/6154443154988404816.jpg" alt="ewan" ><p></p></a></li>
                        <li><a href="." rel="noopener noreferrer"><img src="../../asset/img/6154443154988404817.jpg" alt="ewan" ></a></li>
                        <li><a href="." rel="noopener noreferrer"><img src="../../asset/img/6156791707530366599.jpg" alt="ewan" ></a></li>

                        <li><a href="." rel="noopener noreferrer"><img src="../../asset/img/6154443154988404815.jpg" alt="ewan" ></a></li>
                        <li><a href="." rel="noopener noreferrer"><img src="../../asset/img/6154443154988404816.jpg" alt="ewan" ></a></li>
                        <li><a href="." rel="noopener noreferrer"><img src="../../asset/img/6154443154988404817.jpg" alt="ewan" ></a></li>
                        <li><a href="." rel="noopener noreferrer"><img src="../../asset/img/6156791707530366599.jpg" alt="ewan" ></a></li>

                    </ul>

                    <button class="slide-btn two" id="next-btn2"><img src="../../asset/img/chevron_forward_19dp_000000_FILL0_wght200_GRAD0_opsz20.svg" alt="forward-arrow"></button>


                </div>
            </div>

            <!-- Packages -->
            <section class="package_wrapper">
                <div class="heading three">Packages u cannot miss</div>

                <ul class="packages">

                    <!-- Package 1 -->
                    <li>
                        <a href="." rel="noopener noreferrer"><div class="package one">

                            <div class="image"><img src="../../asset/img/6154443154988404815.jpg" alt="package_picture" width="auto" height="150"></div>

                            <ul>
                                <li><div class="number"><span>Package 1</span></div></li>

                                <li><div class="attractions"><span>Casa la cote + Puerto berde + Juju on the beat + No merk + No dihh + no bruhhhhhhhhhhhhhhhhh + No shi + nosssssssssssssssssssssssssssssssssssssssssssssssssssss  </span></div></li>

                                <li><div class="price"><span>₱67,6767</span></div></li>
                            </ul>
                        </div></a>
                    </li>

                    
                    <!-- Package 2 -->
                    <li>
                        <a href="." rel="noopener noreferrer"><div class="package two">

                            <div class="image"><img src="../../asset/img/6154443154988404816.jpg" alt="package_picture" width="auto" height="150"></div>

                            <ul>
                                <li><div class="number"><span>Package 2</span></div></li>

                                <li><div class="attractions"><span>Casa la cote + Puerto berde + Juju on the beat + No merk + No dihh + no bruhhhhhhhhhhhhhhhhh + No shi + no ssssssssssssssssssssssssssssssssssssssssssssssssss</span></div></li>

                                <li><div class="price"><span>₱67,6767</span></div></li>
                            </ul>
                        </div></a>
                    </li>
                        
                    <!-- Package 3 -->
                    <li>
                        <a href="." rel="noopener noreferrer"><div class="package three">

                            <div class="image"><img src="../../asset/img/6154443154988404816.jpg" alt="package_picture" width="auto" height="150"></div>

                            <ul>
                                <li><div class="number"><span>Package 3</span></div></li>

                                <li><div class="attractions"><span>Casa la cote + Puerto berde + Juju on the beat + No merk + No dihh + no bruhhhhhhhhhhhhhhhhh + No shi + no ssssssssssssssssssssssssssssssssssssssssssssssssss</span></div></li>

                                <li><div class="price"><span>₱67,6767</span></div></li>
                            </ul>
                        </div></a>
                    </li>

                    <!-- Package 4 -->
                    <li>
                        <a href="." rel="noopener noreferrer"><div class="package four">

                            <div class="image"><img src="../../asset/img/6154443154988404816.jpg" alt="package_picture" width="auto" height="150"></div>

                            <ul>
                                <li><div class="number"><span>Package 4</span></div></li>

                                <li><div class="attractions"><span>Casa la cote + Puerto berde + Juju on the beat + No merk + No dihh + no bruhhhhhhhhhhhhhhhhh + No shi + no ssssssssssssssssssssssssssssssssssssssssssssssssss</span></div></li>

                                <li><div class="price"><span>₱67,6767</span></div></li>
                            </ul>
                        </div></a>
                    </li>
                


                </ul>
            </section>            
            
        </section>


    </main>




</body>
</html>