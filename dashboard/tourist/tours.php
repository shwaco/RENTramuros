<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentramuros (Reservation)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../asset/css/calendar.css?v=<?php echo filemtime('../../asset/css/calendar.css'); ?>">
    <link rel="stylesheet" href="../../asset/css/receipt.css?v=<?php echo filemtime('../../asset/css/receipt.css'); ?>">
    <link rel="stylesheet" href="../../asset/css/tours.css?v=<?php echo filemtime('../../asset/css/tours.css'); ?>">

    <script src="../../asset/js/calendar.js?v=<?php echo filemtime('../../asset/js/calendar.js'); ?>" defer></script>
    <script src="../../asset/js/receipt.js?v=<?php echo filemtime('../../asset/js/receipt.js'); ?>" defer></script>

    <script type="module"> 
        import { fetchToursData, submitBookingRequest } from '../../services/api.js'; 
        window.fetchToursData = fetchToursData;
        window.submitBookingRequest = submitBookingRequest; /* NEW: Exposes the POST API */
    </script>
    <script src="../../asset/js/tours.js?v=<?php echo filemtime('../../asset/js/tours.js'); ?>" defer></script>
</head>
<body>
    <div class="reservation-container">
        
        <div class="process-container">
            <div class="steps-container">
                <div class="steps">
                    <div class="circle-1">1</div>
                    <div class="progress-bar-1"></div>
                    <div class="circle-2">2</div>
                    <div class="progress-bar-2"></div>
                    <div class="circle-3">3</div>
                </div>
            </div>
        </div>

        <div class="slider-viewport">
            <div class="slider-track" id="sliderTrack">
                
                <div class="slide-step">
                    <div class="step-1">
                        <div class="time-date-container">
                            <div class="text-box"><span class="time-date-text">When will this journey be?</span></div>  
                            <span class="time-date-label">TIME & DATE</span>
                            <div class="dropdown-container">
                                <div class="time-dropdown">
                                    <div class="time-select" id="time-select-btn"><span class="time-selected" id="time-display">06:00 PM</span></div>
                                    <div class="time-selection" id="time-menu">
                                        <div class="time-columns">
                                            <div class="scroll-column" id="hour-column"></div>
                                            <div class="time-colon">:</div>
                                            <div class="scroll-column" id="minute-column"></div>
                                            <div class="ampm-column">
                                                <div class="time-option ampm-option selected" data-type="ampm" data-val="AM">AM</div>
                                                <div class="time-option ampm-option" data-type="ampm" data-val="PM">PM</div>
                                            </div>
                                        </div>
                                        <button type="button" class="time-confirm-btn" id="confirm-time-btn">Confirm</button>
                                    </div>
                                </div> 
                                <div class="date-dropdown">
                                    <div class="date-select" id="date-select-btn">
                                        <span class="date-selected" id="date-display">April 25, 2026</span>
                                        <div class="date-caret"></div>
                                    </div>
                                    <div class="calendar-popup" id="calendar-popup">
                                        <div class="calendar-card">
                                            <div class="calendar-header">
                                                <button class="nav-arrow">&lt;</button>
                                                <h3 class="current-month">April 2026</h3>
                                                <button class="nav-arrow">&gt;</button>
                                            </div>
                                            <div class="weekday-labels">
                                                <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                                            </div>
                                            <div class="calendar-grid"></div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="tourists-quantity-conatiner">
                            <div class="text-box"><span class="tourists-quantity-text">How many tourist are in this journey? </span></div>
                            <div class="quantity-container">
                                <div class="adults-container">
                                    <div class="adults-label-container"><span class="adults-label">ADULTS</span><span class="adults-req-age">(18 years old and above)</span></div>
                                    <div class="adult-counter-row">
                                        <div class="adult-quantity-counter">
                                            <button type="button" class="minus" id="adult-minus" onclick="updateTouristCount('adult', -1)">-</button>
                                            <span class="adult-count" id="adult-count-display">2</span>
                                            <button type="button" class="plus" id="adult-plus" onclick="updateTouristCount('adult', 1)">+</button>
                                        </div>
                                        <div class="senior-notice-container" onclick="toggleSeniorNotice()" style="cursor: pointer;">
                                            <span class="notice-circle" id="senior-circle"></span>
                                            <span class="senior-notice-text">Includes seniors (ages 60+)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="children-container">
                                    <div class="children-label-container"><span class="children-label">CHILDREN</span><span class="children-req-age">(2 to 17 years old)</span></div>
                                    <div class="children-quantity-counter">
                                        <button type="button" class="minus" id="child-minus" onclick="updateTouristCount('child', -1)">-</button>
                                        <span class="adult-count" id="child-count-display">0</span> 
                                        <button type="button" class="plus" id="child-plus" onclick="updateTouristCount('child', 1)">+</button>
                                    </div>
                                </div>
                                <div class="infants-container">
                                    <div class="infants-label-container"><span class="infants-label">INFANTS</span><span class="infants-req-age">(Under 2 years old)</span></div>
                                    <div class="infants-quantity-counter">
                                        <button type="button" class="minus" id="infant-minus" onclick="updateTouristCount('infant', -1)">-</button>
                                        <span class="adult-count" id="infant-count-display">0</span>
                                        <button type="button" class="plus" id="infant-plus" onclick="updateTouristCount('infant', 1)">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="package-confirm-container">
                            <div class="text-box"><span class="availing-package-text">Availing for package?</span></div>
                            <div class="confirming-package-button-container">
                                <button type="button" class="no-package" id="btn-no" onclick="selectPackageOption(false)">NO</button>
                                <button type="button" class="yes-package" id="btn-yes" onclick="selectPackageOption(true)">YES</button>
                            </div>
                        </div>
                        <div class="prev-next-container" style="justify-content: flex-end;">
                            <button class="next-button" onclick="nextStep()">NEXT</button>
                        </div>
                    </div>
                </div>

                <div class="slide-step">
                    
                    <div class="step-2-packages" id="step2Packages">
                        <div class="packages-container">
                            <div class="text-box"><span class="packages-text">Choose a package to experience</span></div>  
                            <div class="package-options-container" id="dynamic-packages">
                                
                            </div>
                        </div>
                        
                        <div class="select-vehicle-packages-conatiner">
                            <div class="text-box"><span class="select-vehicle-text">Choose a vehicle to ride</span></div>
                            <div class="vehicle-container" id="dynamic-package-vehicles">
                                <div class="no-vehicle vehicle-card" id="veh-none" onclick="selectVehicle('veh-none', 'None')">
                                    <span class="none-text">NONE</span>
                                </div>
                                </div>
                        </div>
                    </div>

                    <div class="step-2-custom" id="step2Custom" style="display: none;">
                        <div class="attractions-container">
                            <div class="text-box"><span class="attractions-text">Choose the attractions to experience</span></div>
                            <div class="attractions-options-container">
                                
                                <div class="attraction-layer-1" id="dynamic-attractions-layer-1">
                                    </div>
                                
                                <div class="attraction-layer-2" id="dynamic-attractions-layer-2">
                                    </div>
                            </div>
                        </div>
                        
                        <div class="custom-select-vehicle-conatiner">
                            <div class="text-box">
                                <span class="custom-select-vehicle-text">Choose a vehicle to ride</span>
                            </div> 
                            <div class="custom-vehicle-container" id="dynamic-custom-vehicles">
                                <div class="custom-no-vehicle custom-vehicle-card" id="custom-veh-none" onclick="selectCustomVehicle('custom-veh-none', 'None')">
                                    <span class="none-text">NONE</span>
                                </div>
                                </div>
                        </div>
                    </div>

                    <div class="prev-next-container">
                        <button class="prev-button" onclick="prevStep()">PREVIOUS</button>
                        <button class="next-button" onclick="nextStep()">NEXT</button>
                    </div>
                </div>

                <div class="slide-step">
                    <div class="step-3">
                        <div class="contact-info-container">
                            <div class="text-box"><span class="contact-info-text">Provide your contact information</span></div>
                            <div class="info-content-container">
                                
                                    <div class="first-name-container">
                                        <div class="full-name-label-container"><span class="full-name-label">FIRST NAME</span></div>
                                        <input type="text" id="contact-first-name" class="name-input" placeholder="Enter your first name" oninput="this.value = this.value.replace(/[^a-zA-Z .\-]/g, '').toUpperCase()">
                                    </div>

                                    <div class="last-name-container">
                                        <div class="full-name-label-container"><span class="full-name-label">LAST NAME</span></div>
                                        <input type="text" id="contact-last-name" class="name-input" placeholder="Enter your last name" oninput="this.value = this.value.replace(/[^a-zA-Z .\-]/g, '').toUpperCase()">
                                    </div>
                                
                                    <div class="email-container">
                                        <div class="email-label-container"><span class="email-label">EMAIL ADDRESS</span></div>
                                        <input type="text" id="contact-email" class="email-input" placeholder="Enter your email address">
                                    </div>

                                    <div class="phone-container">
                                        <div class="phone-label-container"><span class="phone-label">PHONE NUMBER</span></div>
                                        <input type="tel" id="contact-phone" class="phone-input" placeholder="Enter your phone number" maxlength="11" 
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                                            onfocus="if(this.value === '') this.value = '09';" 
                                            onblur="if(this.value === '09') this.value = '';">
                                    </div>
                            </div>
                        </div>
                        <div class="prev-next-container">
                            <button class="prev-button" onclick="prevStep()">PREVIOUS</button>
                            <button class="submit-button" onclick="submitReservation()">SUBMIT</button>
                        </div>
                    </div>
                </div>

            </div> 
        </div> 
    </div>
    
    <div class="modal-overlay" id="confirmationModal">
        <article class="receipt-container" aria-labelledby="receipt-id">
            <header style="display: flex; justify-content: right; align-items: right; width: 100%; border-bottom: 1px solid #e5e7eb; padding-top: 1rem; padding-bottom: 1rem;">
                <button aria-label="Close Receipt" class="close-btn" id="closeModal">&times;</button>
            </header>

            <time id="modal-date-time" style="display: block; text-align: right; font-size: 0.8rem; color: #000000; margin-top: 1.5rem; margin-bottom: 2rem; font-family: 'Roboto Condensed', sans-serif; font-weight: 700;">
            </time>

            <section aria-labelledby="tourist-heading">
                <h3 id="tourist-heading" style="font-weight: 700; font-size:0.9rem; margin: 0 0 1rem 0; color:#000000; font-family: 'Roboto Condensed', sans-serif;">TOURIST</h3>
                <dl style="margin: 0; padding: 0;">
                    <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem; padding-left: 1.25rem;">
                        <dt id="modal-adult-label" style="font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">ADULTS</dt>
                        <dd style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">(18 years old and above)</dd>
                        <dd id="modal-adults" style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">0</dd>
                    </div>
                    <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem; padding-left: 1.25rem;">
                        <dt style="font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">CHILDREN</dt>
                        <dd style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">(2 to 17 years old)</dd>
                        <dd id="modal-children" style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">0</dd>
                    </div>
                    <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:1.5rem; font-size:0.85rem; padding-left: 1.25rem;">
                        <dt style="font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">INFANTS</dt>
                        <dd style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">(under 2 years old)</dd>
                        <dd id="modal-infants" style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">0</dd>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.85rem;">
                        <dt style="font-weight: 700; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">PACKAGE</dt>
                        <dd id="modal-package" style="font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">NONE</dd>
                    </div>
                </dl>
            </section>

            <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;" aria-hidden="true">

            <section aria-labelledby="itinerary-heading">
                <h3 id="itinerary-heading" style="font-weight: 700; font-size:0.9rem; margin: 0 0 1rem 0; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ITINERARY</h3>
                <ul id="modal-itinerary-list" style="list-style: none; padding: 0; display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; font-size: 0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0 0 1.5rem 0;">
                </ul>
                <dl style="margin: 0; padding: 0;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; font-size: 0.85rem;">
                        <dt style="font-weight: 700; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">VEHICLE</dt>
                        <dd id="modal-vehicle" style="font-family: 'Roboto Condensed', sans-serif; color: #000000; text-transform: uppercase; text-align: center; margin: 0;">NONE</dd>
                        <dd id="modal-vehicle-quantity" style="font-family: 'Roboto Condensed', sans-serif; color: #000000; text-align: right; font-weight: bold; margin: 0;"></dd>
                    </div>
                </dl>
            </section>

            <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;" aria-hidden="true">

            <section aria-labelledby="contact-heading">
                <h3 id="contact-heading" style="font-weight: 700; font-size:0.9rem; margin: 0 0 1rem 0; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CONTACT INFORMATION</h3>
                <address style="font-style: normal; margin: 0; padding: 0;">
                    <dl style="margin: 0; padding: 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
                            <dt style="font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">FULL NAME:</dt>
                            <dd id="modal-full-name" style="font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">---</dd>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
                            <dt style="font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">EMAIL ADDRESS:</dt>
                            <dd id="modal-email" style="font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">---</dd>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.85rem;">
                            <dt style="font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">PHONE NUMBER:</dt>
                            <dd id="modal-phone" style="font-family: 'Roboto Condensed', sans-serif; color: #000000; margin: 0;">---</dd>
                        </div>
                    </dl>
                </address>
            </section>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; margin-bottom: 1.5rem; border-top: 1px solid #e5e7eb; padding-top: 1rem;">
                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    <span style="font-weight: 700; font-family: 'Roboto Condensed', sans-serif; color: #000000; font-size: 0.9rem;">TOTAL FEE:</span>
                    <span id="modal-total-fee" style="font-weight: 600; font-family: 'Roboto Condensed', sans-serif; color: #109620; font-size: 1.1rem; font-style: italic;">₱0</span>
                </div>
            </div>

            <footer style="display: flex; justify-content: flex-end">
                <button class="accept-btn" onclick="confirmFinalAcceptance()" aria-label="Accept Tour" style="background-color: #109620; color: #ffffff; border: none; padding: 0.6rem 2.5rem; font-size: 1.1rem; font-weight: 900; font-family: 'Roboto Condensed', sans-serif; border-radius: 2px; cursor: pointer; transition: background-color 0.2s;">
                    ACCEPT
                </button>
            </footer>
        </article>
    </div>

</body>
</html>