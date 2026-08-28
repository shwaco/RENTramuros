<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "tourist") {
    header("Location: ../login_page/login.php");
    exit();
}
?>
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

   <!-- CSS Path Fixes: Pointing to the same folder[cite: 23] -->
    <link rel="stylesheet" href="calendar_latest.css?v=<?php echo filemtime('calendar_latest.css'); ?>">
    <link rel="stylesheet" href="tours_latest.css?v=<?php echo filemtime('tours_latest.css'); ?>">
    
    <!-- Path Fix: Reaching root shared components from pages/tour_page/[cite: 23] -->
    <link rel="stylesheet" href="../../shared/components/receipt/tour_details/tour_details.css">
    
    <!-- JS Path Fixes: Pointing to the same folder[cite: 23] -->
    <script src="calendar_latest.js?v=<?php echo filemtime('calendar_latest.js'); ?>" defer></script>
    

    <script type="module"> 
        // Path Fix: Reaching root services from pages/tour_page/[cite: 23]
        import { fetchToursData, submitBookingRequest } from '../../services/tours_api.js'; 
        window.fetchToursData = fetchToursData;
        window.submitBookingRequest = submitBookingRequest;
    </script>
    
    <script src="tours_latest.js?v=<?php echo filemtime('tours_latest.js'); ?>" defer></script>
    <script src="receipt_send_input.js?v=<?php echo filemtime('receipt_send_input.js'); ?>" defer></script>
    <script src="receipt_latest.js?v=<?php echo filemtime('receipt_latest.js'); ?>" defer></script>
    <script src="receipt_store.js?v=<?php echo filemtime('receipt_store.js'); ?>" defer></script>
    <script src="tours_renderer.js?v=<?php echo filemtime('tours_renderer.js'); ?>" defer></script>

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
                                        <span class="date-selected" id="date-display">April 30, 2026</span>
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
                                <div class="no-vehicle vehicle-card" id="veh-none" onclick="selectVehicle('veh-none', 'None', 0)">
                                    <span class="none-text">NONE</span>
                                </div>
                                </div>
                        </div>
                    </div>

                    <div class="step-2-custom" id="step2Custom" style="display: none;">
                        <div class="attractions-container">
                            <div class="text-box"><span class="attractions-text">Choose the attractions to experience</span></div>
                            <div class="attractions-options-container" id="dynamic-attractions-container">

                            </div>
                        </div>
                        
                        <div class="custom-select-vehicle-conatiner">
                            <div class="text-box">
                                <span class="custom-select-vehicle-text">Choose a vehicle to ride</span>
                            </div> 
                            <div class="custom-vehicle-container" id="dynamic-custom-vehicles">
                                <div class="custom-no-vehicle custom-vehicle-card" id="custom-veh-none" onclick="selectCustomVehicle('custom-veh-none', 'None', 0)">
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
    
    <div class="modal-overlay" id="confirmationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000; backdrop-filter: blur(3px);">
        
        <article class="receipt-card" style="position: relative; max-height: 100vh; overflow-y: auto; width: 90%; max-width: 440px;">
            
            <button aria-label="Close" class="close-btn" id="closeModal" style="position: absolute; top: 0.5rem; right: 1.5rem; background: none; border: none; font-size: 2rem; cursor: pointer; color: #000000;">&times;</button>

            <div class="receipt-header">
            </div>

            <div class="receipt-date" id="modal-date-time">
            </div>

            <div class="section-label">TOURIST</div>
            <div class="tourist-grid">
                <span class="tourist-label" id="modal-adult-label">ADULTS</span>
                <span class="tourist-sub">(18 years old and above)</span>
                <span class="tourist-val" id="modal-adults">0</span>
            </div>
            <div class="tourist-grid">
                <span class="tourist-label">CHILDREN</span>
                <span class="tourist-sub">(2 to 17 years old)</span>
                <span class="tourist-val" id="modal-children">0</span>
            </div>
            <div class="tourist-grid" style="margin-bottom: 1.5rem;">
                <span class="tourist-label">INFANTS</span>
                <span class="tourist-sub">(under 2 years old)</span>
                <span class="tourist-val" id="modal-infants">0</span>
            </div>

            <div class="pkg-row" style="margin-top: 1rem;">
                <span style="font-weight:700;">PACKAGE</span>
                <span id="modal-package">NONE</span>
            </div>

            <hr class="divider-dashed">

            <div class="section-label">ITINERARY</div>
            <div id="modal-itinerary-list" class="itinerary-grid">
                <!-- JS will inject destinations here -->
            </div>

            <div class="vehicle-grid">
                <span style="font-weight:700;">VEHICLE</span>
                <span id="modal-vehicle" style="text-transform: uppercase; text-align: center;">NONE</span>
                <span id="modal-vehicle-quantity" style="text-align: right; font-weight: bold;">0</span>
            </div>

            <hr class="divider-dashed">

            <div class="section-label">CONTACT INFORMATION</div>
            <div class="contact-row">
                <span>FULL NAME:</span>
                <span id="modal-full-name" style="text-transform: uppercase;">---</span>
            </div>
            <div class="contact-row">
                <span>EMAIL ADDRESS:</span>
                <span id="modal-email">---</span>
            </div>
            <div class="contact-row" style="margin-bottom: 1.5rem;">
                <span>PHONE NUMBER:</span>
                <span id="modal-phone">---</span>
            </div>

            <hr class="divider-solid">

            <div class="totals-wrapper" style="display: flex; justify-content: space-between; align-items: flex-end;">
                <div class="totals-grid" style="flex-grow: 1;">
                    <span class="total-label">TOTAL FEE:</span>
                    <span id="modal-base-fee" class="total-val">₱0</span> 
                    <span class="total-label">TOUR GUIDE FEE:</span>
                    <span class="total-val">₱1,000 - ₱1,500</span>
                    
                    <span class="grand-label">GRAND TOTAL:</span>
                    <span id="modal-grand-total" class="grand-val" style="color: #109620;">₱0</span>
                </div>
                
                <!-- Submit Button -->
                <div style="margin-left: 2rem; padding-bottom: 0.5rem;">
                    <button class="accept-btn" onclick="confirmFinalAcceptance()" style="background-color: #109620; color: #ffffff; border: none; padding: 0.8rem 2.5rem; font-size: 1.1rem; font-weight: 900; font-family: 'Roboto Condensed', sans-serif; border-radius: 4px; cursor: pointer;">  
                        SUBMIT
                    </button>
                </div>
            </div>
        </article>
    </div>

    <script>
        const modalOverlay = document.getElementById('confirmationModal');
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    if (modalOverlay.classList.contains('show')) {
                        modalOverlay.style.display = 'flex';
                    } else {
                        modalOverlay.style.display = 'none';
                    }
                }
            });
        });
        observer.observe(modalOverlay, { attributes: true });
    </script>
</body>
</html>