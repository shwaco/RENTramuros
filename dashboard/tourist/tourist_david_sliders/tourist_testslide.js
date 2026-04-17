// --- MASTER RESERVATION DATA RECORD ---
// This is exactly what your backend developer will send to the database!
// --- MASTER RESERVATION DATA RECORD ---
let reservationData = {
    wantsPackage: null, 
    selectedPackage: null, 
    selectedVehicle: null,
    vehicleQuantity: 0, // === ALREADY ADDED: Tracks how many vehicles ===
    customAttractions: [], 
    tourists: {
        adults: 2,
        children: 0,
        infants: 0
    },
    includesSeniors: false,
    
    // NEW: Added contact info storage
    contactInfo: {
        name: "",
        email: "",
        phone: ""
    }
};

// --- SLIDER PROGRESSION LOGIC ---
let currentStep = 1;

function updateForm() {
    // 1. SLIDE THE CONTAINERS
    const track = document.getElementById('sliderTrack');
    // Calculates how far left to push the track: 0%, -33.333%, or -66.666%
    const translation = (currentStep - 1) * -33.333; 
    track.style.transform = `translateX(${translation}%)`;

    // 2. RESET ALL TOP CIRCLES TO WHITE (INACTIVE)
    document.querySelector('.circle-1').style.backgroundColor = '#ffffff';
    document.querySelector('.circle-1').style.color = '#000000';
    document.querySelector('.progress-bar-1').style.backgroundColor = '#ffffff';
    
    document.querySelector('.circle-2').style.backgroundColor = '#ffffff';
    document.querySelector('.circle-2').style.color = '#000000';
    document.querySelector('.progress-bar-2').style.backgroundColor = '#ffffff';
    
    document.querySelector('.circle-3').style.backgroundColor = '#ffffff';
    document.querySelector('.circle-3').style.color = '#000000';

    // 3. COLOR THE ACTIVE STEPS RED
    if (currentStep >= 1) {
        document.querySelector('.circle-1').style.backgroundColor = '#8A2814';
        document.querySelector('.circle-1').style.color = '#ffffff';
    }
    if (currentStep >= 2) {
        document.querySelector('.progress-bar-1').style.backgroundColor = '#8A2814';
        document.querySelector('.circle-2').style.backgroundColor = '#8A2814';
        document.querySelector('.circle-2').style.color = '#ffffff';
    }
    if (currentStep >= 3) {
        document.querySelector('.progress-bar-2').style.backgroundColor = '#8A2814';
        document.querySelector('.circle-3').style.backgroundColor = '#8A2814';
        document.querySelector('.circle-3').style.color = '#ffffff';
    }
}

function nextStep() {
    // If they are on Step 1, run our validations
    if (currentStep === 1) {
        
        // 1. ADULT VALIDATION: Must have at least 1 adult
        if (reservationData.tourists.adults === 0) {
            alert("You must have at least 1 adult to proceed with the journey.");
            return; // Stops the slider from moving!
        }

        // 2. PACKAGE VALIDATION: Must pick YES or NO
        if (reservationData.wantsPackage === null) {
            alert("Please select YES or NO for the package before continuing.");
            return; // Stops the slider from moving!
        }

        // They passed both checks! Let's arrange Step 2 before we slide to it.
        const pkgDiv = document.getElementById('step2Packages');
        const customDiv = document.getElementById('step2Custom');

        if (reservationData.wantsPackage === true) {
            pkgDiv.style.display = 'flex';
            customDiv.style.display = 'none';
        } else {
            pkgDiv.style.display = 'none';
            customDiv.style.display = 'flex';
        }
    }

    // Normal sliding logic (applies to moving from Step 1->2 and Step 2->3)
    if (currentStep < 3) {
        currentStep++;
        updateForm();
        window.scrollTo({ top: 0, behavior: 'smooth' }); 
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        updateForm();
        window.scrollTo({ top: 0, behavior: 'smooth' }); // Scrolls back to top on prev
    }
}

// Run once immediately when the page loads so Step 1 is colored red!
updateForm(); 

// --- STEP 1: TIME SELECTION LOGIC ---
document.addEventListener("DOMContentLoaded", () => {
    const hourCol = document.getElementById("hour-column");
    const minCol = document.getElementById("minute-column");
    const timeSelectBtn = document.getElementById("time-select-btn");
    const timeMenu = document.getElementById("time-menu");
    const timeDisplay = document.getElementById("time-display");
    const confirmBtn = document.getElementById("confirm-time-btn");

    let selectedHour = "06";
    let selectedMinute = "00";
    let selectedAmPm = "AM";

    // Generate Hours (01 to 12)
    for (let i = 1; i <= 12; i++) {
        let val = i < 10 ? "0" + i : i.toString();
        let div = document.createElement("div");
        div.className = `time-option hour-option ${val === selectedHour ? 'selected' : ''}`;
        div.innerText = val;
        div.dataset.val = val;
        div.dataset.type = "hour";
        hourCol.appendChild(div);
    }

    // Generate Minutes (00 to 59)
    for (let i = 0; i <= 59; i++) {
        let val = i < 10 ? "0" + i : i.toString();
        let div = document.createElement("div");
        div.className = `time-option minute-option ${val === selectedMinute ? 'selected' : ''}`;
        div.innerText = val;
        div.dataset.val = val;
        div.dataset.type = "minute";
        minCol.appendChild(div);
    }

    // Handle Clicks inside Time Menu
    timeMenu.addEventListener("click", (e) => {
        if (e.target.classList.contains("time-option")) {
            let type = e.target.dataset.type;
            let val = e.target.dataset.val;

            let siblings = e.target.parentElement.querySelectorAll('.time-option');
            siblings.forEach(el => el.classList.remove("selected"));
            e.target.classList.add("selected");

            if (type === "hour") selectedHour = val;
            if (type === "minute") selectedMinute = val;
            if (type === "ampm") selectedAmPm = val;
        }
    });

    // Toggle Time Menu (and close Calendar if open)
    timeSelectBtn.addEventListener("click", () => {
        const calendarPopup = document.getElementById("calendar-popup");
        if(calendarPopup) calendarPopup.classList.remove("show"); // Close calendar
        timeMenu.classList.toggle("show");
    });

    // Confirm Button
    confirmBtn.addEventListener("click", () => {
        timeDisplay.innerText = `${selectedHour}:${selectedMinute} ${selectedAmPm}`;
        timeMenu.classList.remove("show");
    });
});

// --- STEP 1: DATE CALENDAR POPUP LOGIC ---
document.addEventListener("DOMContentLoaded", () => {
    const dateSelectBtn = document.getElementById("date-select-btn");
    const calendarPopup = document.getElementById("calendar-popup");
    const dateDisplay = document.getElementById("date-display");
    const timeMenu = document.getElementById("time-menu");

    dateSelectBtn.addEventListener("click", () => {
        if(timeMenu) timeMenu.classList.remove("show"); // Close time menu
        calendarPopup.classList.toggle("show");
    });

    calendarPopup.addEventListener("click", (e) => {
        // Find the closest calendar-day element that was clicked
        const dayCell = e.target.closest('.calendar-day');
        
        if (dayCell) {
            // Grab the day number from the clicked cell
            const dayNumber = dayCell.querySelector('.day-number').innerText;
            // Grab the current month and year from the calendar header
            const currentMonthYear = document.querySelector('.current-month').innerText;
            
            // Split "March 2026" into "March" and "2026" to format the text nicely
            const [month, year] = currentMonthYear.split(' ');
            
            // Update the display text to match the format: Month DD, YYYY
            dateDisplay.innerText = `${month} ${dayNumber}, ${year}`;
            
            // Automatically hide the calendar popup after 150ms 
            setTimeout(() => {
                 calendarPopup.classList.remove("show");
            }, 150); 
        }
    });
});

// --- STEP 1: TOURIST COUNTER LOGIC ---
function updateTouristCount(type, change) {
    let currentCount = 0;
    
    if (type === 'adult') {
        currentCount = reservationData.tourists.adults;
    } else if (type === 'child') {
        currentCount = reservationData.tourists.children;
    } else if (type === 'infant') {
        currentCount = reservationData.tourists.infants;
    }

    let newCount = currentCount + change;

    if (newCount < 0) {
        newCount = 0;
    }

    if (type === 'adult') reservationData.tourists.adults = newCount;
    if (type === 'child') reservationData.tourists.children = newCount;
    if (type === 'infant') reservationData.tourists.infants = newCount;

    if (type === 'adult') {
        document.getElementById('adult-count-display').innerText = newCount;
    } else if (type === 'child') {
        document.getElementById('child-count-display').innerText = newCount;
    } else if (type === 'infant') {
        document.getElementById('infant-count-display').innerText = newCount;
    }

    console.log("Current Data:", reservationData); 
}

// --- STEP 1: SENIOR CHECKBOX LOGIC ---
function toggleSeniorNotice() {
    reservationData.includesSeniors = !reservationData.includesSeniors;

    const circle = document.getElementById('senior-circle');

    if (reservationData.includesSeniors === true) {
        circle.classList.add('active');
    } else {
        circle.classList.remove('active');
    }
    
    console.log("Current Data:", reservationData); 
}

// --- STEP 1: YES/NO PACKAGE LOGIC ---
function selectPackageOption(wantsPackage) {
    reservationData.wantsPackage = wantsPackage; 

    const btnYes = document.getElementById('btn-yes');
    const btnNo = document.getElementById('btn-no');

    btnYes.classList.remove('active-selection');
    btnNo.classList.remove('active-selection');

    if (wantsPackage === true) {
        btnYes.classList.add('active-selection');
    } else {
        btnNo.classList.add('active-selection');
    }
    
    console.log("Current Data:", reservationData); 
}

// --- STEP 2: PACKAGE AND VEHICLE LOGIC (WITH UNSELECT) ---

function selectPackage(packageName) {
    if (reservationData.selectedPackage === packageName) {
        reservationData.selectedPackage = null; 
        document.getElementById('pkg-1').classList.remove('selected-card');
        document.getElementById('pkg-2').classList.remove('selected-card');
        document.getElementById('pkg-3').classList.remove('selected-card');
    } else {
        reservationData.selectedPackage = packageName;
        document.getElementById('pkg-1').classList.remove('selected-card');
        document.getElementById('pkg-2').classList.remove('selected-card');
        document.getElementById('pkg-3').classList.remove('selected-card');

        if (packageName === 'Package 1') document.getElementById('pkg-1').classList.add('selected-card');
        if (packageName === 'Package 2') document.getElementById('pkg-2').classList.add('selected-card');
        if (packageName === 'Package 3') document.getElementById('pkg-3').classList.add('selected-card');
    }
    console.log("Current Data:", reservationData);
}

function selectVehicle(vehicleName) {
    if (reservationData.selectedVehicle === vehicleName) {
        reservationData.selectedVehicle = null;
        
        // === NEW ADDITION: Reset quantity to 0 when UNSELECTED ===
        reservationData.vehicleQuantity = 0;
        // =========================================================

        const allVehicles = document.querySelectorAll('.vehicle-card');
        allVehicles.forEach(v => v.classList.remove('selected-card'));
    } else {
        reservationData.selectedVehicle = vehicleName;
        
        // === NEW ADDITION: Set quantity to 1 when a new vehicle is SELECTED ===
        reservationData.vehicleQuantity = 1;
        document.querySelectorAll('.veh-count').forEach(el => el.innerText = '1');

        const allVehicles = document.querySelectorAll('.vehicle-card');
        allVehicles.forEach(v => v.classList.remove('selected-card'));

        if (vehicleName === 'None') document.getElementById('veh-none').classList.add('selected-card');
        if (vehicleName === 'Tuktuk') document.getElementById('veh-1').classList.add('selected-card');
        if (vehicleName === 'Kalesa') document.getElementById('veh-2').classList.add('selected-card');
        if (vehicleName === 'Tranvia') document.getElementById('veh-3').classList.add('selected-card');
    }
    console.log("Current Data:", reservationData);
}

// --- STEP 2 CUSTOM LOGIC ---

function toggleAttraction(attractionName, elementId) {
    const array = reservationData.customAttractions;
    const index = array.indexOf(attractionName);
    const card = document.getElementById(elementId);

    if (index > -1) {
        array.splice(index, 1);
        card.classList.remove('selected-card');
    } else {
        array.push(attractionName);
        card.classList.add('selected-card');
    }
    console.log("Current Attractions:", reservationData.customAttractions);
}

function selectCustomVehicle(vehicleName) {
    if (reservationData.selectedVehicle === vehicleName) {
        reservationData.selectedVehicle = null;
        
        // === NEW ADDITION: Reset quantity to 0 when UNSELECTED ===
        reservationData.vehicleQuantity = 0;

        document.querySelectorAll('.custom-vehicle-card').forEach(v => v.classList.remove('selected-card'));
    } else {
        reservationData.selectedVehicle = vehicleName;
        
        // === NEW ADDITION: Set quantity to 1 when a new vehicle is SELECTED ===
        reservationData.vehicleQuantity = 1;
        document.querySelectorAll('.veh-count').forEach(el => el.innerText = '1');

        document.querySelectorAll('.custom-vehicle-card').forEach(v => v.classList.remove('selected-card'));
        if (vehicleName === 'None') document.getElementById('custom-veh-none').classList.add('selected-card');
        if (vehicleName === 'Tuktuk') document.getElementById('custom-veh-1').classList.add('selected-card');
        if (vehicleName === 'Kalesa') document.getElementById('custom-veh-2').classList.add('selected-card');
        if (vehicleName === 'Tranvia') document.getElementById('custom-veh-3').classList.add('selected-card');
    }
    console.log("Current Data:", reservationData);
}

// === NEW ADDITION: BRAND NEW FUNCTION TO HANDLE THE +/- BUTTONS ON VEHICLES ===
function updateVehicleCount(change, event) {
    event.stopPropagation(); 

    let newCount = reservationData.vehicleQuantity + change;

    // Prevent them from going below 1 (if the vehicle is selected, they must have at least 1)
    if (newCount < 1) {
        newCount = 1;
    }

    reservationData.vehicleQuantity = newCount;

    // Find the counter on the screen and update the number
    const activeCard = event.target.closest('.vehicle-card') || event.target.closest('.custom-vehicle-card');
    if (activeCard) {
        activeCard.querySelector('.veh-count').innerText = newCount;
    }

    console.log("Vehicle Quantity updated:", reservationData.vehicleQuantity);
}
// --- MODAL LOGIC STEP 3 ---

function submitReservation() {
    // 1. Grab values from Step 3 inputs
    const name = document.getElementById('contact-name').value.trim();
    const email = document.getElementById('contact-email').value.trim();
    const phone = document.getElementById('contact-phone').value.trim();

    if (!name || !email || !phone) {
        alert("Please fill out all contact details.");
        return;
    }

    // 2. Save to data object
    reservationData.contactInfo = { name, email, phone };

    // 3. Inject data into the Modal
    document.getElementById('modal-adults').innerText = reservationData.tourists.adults;
    document.getElementById('modal-children').innerText = reservationData.tourists.children;
    document.getElementById('modal-infants').innerText = reservationData.tourists.infants;
    
    // Dynamic Senior Label Logic
    const adultLabel = document.getElementById('modal-adult-label');
    if (reservationData.includesSeniors === true) {
        adultLabel.innerText = "ADULTS & SENIORS";
    } else {
        adultLabel.innerText = "ADULTS";
    }

    // Package Logic
    document.getElementById('modal-package').innerText = reservationData.wantsPackage ? (reservationData.selectedPackage || "YES") : "NO";
    
    // Vehicle Logic
    document.getElementById('modal-vehicle').innerText = reservationData.selectedVehicle || "NONE";
    
    // Contact Info Logic
    document.getElementById('modal-full-name').innerText = name;
    document.getElementById('modal-email').innerText = email;
    document.getElementById('modal-phone').innerText = phone;

    // Date Logic
    const travelDate = document.getElementById('date-display').innerText;
    const travelTime = document.getElementById('time-display').innerText;
    document.getElementById('modal-date-time').innerText = `${travelDate} ; ${travelTime}`;

    // Itinerary/Attractions Logic
    const itineraryList = document.getElementById('modal-itinerary-list');
    itineraryList.innerHTML = ""; // Clear old list
    if (reservationData.customAttractions.length > 0) {
        reservationData.customAttractions.forEach(attr => {
            const li = document.createElement('li');
            li.innerText = `• ${attr}`;
            itineraryList.appendChild(li);
        });
    } else {
        itineraryList.innerHTML = "<li class='no-itinerary-text'>No custom attractions selected</li>";
    }

    // 4. SHOW THE MODAL
    document.getElementById('confirmationModal').classList.add('show');

    // ... existing modal logic ...

    // Package Logic
    document.getElementById('modal-package').innerText = reservationData.wantsPackage ? (reservationData.selectedPackage || "YES") : "NO";
    
    // --- UPDATED: Vehicle Logic ---
    const vehicleDisplay = document.getElementById('modal-vehicle');
    const vehicleQuantityDisplay = document.getElementById('modal-vehicle-quantity');
    
    if (reservationData.selectedVehicle && reservationData.selectedVehicle !== 'None') {
        vehicleDisplay.innerText = reservationData.selectedVehicle;
        // Inject the quantity number!
        vehicleQuantityDisplay.innerText = reservationData.vehicleQuantity; 
    } else {
        vehicleDisplay.innerText = "NONE";
        // Hide the quantity if they picked NONE
        vehicleQuantityDisplay.innerText = ""; 
    }
    // ------------------------------
    
    // Contact Info Logic
    document.getElementById('modal-full-name').innerText = name;
// ... rest of your modal logic ...

}

// Function to close modal when clicking the X
document.getElementById('closeModal').addEventListener('click', () => {
    document.getElementById('confirmationModal').classList.remove('show');
});

// Function for the final green ACCEPT button
function confirmFinalAcceptance() {
    alert("Thank you! Your reservation for RENTramuros has been submitted.");
    location.reload(); 
}

/* BACKEND HAND-OFF (Placeholder) */
function sendDataToDatabase(data) {
    const finalJSON = JSON.stringify(data, null, 2);
    console.log("FINAL JSON PAYLOAD:", finalJSON);
}