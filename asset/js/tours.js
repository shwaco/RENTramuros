// --- MASTER RESERVATION DATA RECORD ---
let reservationData = {
    wantsPackage: null, 
    selectedPackage: null, 
    selectedVehicle: null,
    vehicleQuantity: 0,
    customAttractions: [], 
    tourists: {
        adults: 2,
        children: 0,
        infants: 0
    },
    includesSeniors: false,
    

    contactInfo: {
        name: "",
        email: "",
        phone: ""
    }
};

// --- SLIDER PROGRESSION LOGIC ---
let currentStep = 1;

function updateForm() {
    const track = document.getElementById('sliderTrack');
    const translation = (currentStep - 1) * -33.333; 
    track.style.transform = `translateX(${translation}%)`;

    document.querySelector('.circle-1').style.backgroundColor = '#ffffff';
    document.querySelector('.circle-1').style.color = '#000000';
    document.querySelector('.progress-bar-1').style.backgroundColor = '#ffffff';
    
    document.querySelector('.circle-2').style.backgroundColor = '#ffffff';
    document.querySelector('.circle-2').style.color = '#000000';
    document.querySelector('.progress-bar-2').style.backgroundColor = '#ffffff';
    
    document.querySelector('.circle-3').style.backgroundColor = '#ffffff';
    document.querySelector('.circle-3').style.color = '#000000';

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
    if (currentStep === 1) {
        
        if (reservationData.tourists.adults === 0) {
            alert("You must have at least 1 adult to proceed with the journey.");
            return;
        }

        if (reservationData.wantsPackage === null) {
            alert("Please select YES or NO for the package before continuing.");
            return;
        }

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

    if (currentStep === 2) {
        
        // 1. Check Packages/Attractions
        if (reservationData.wantsPackage === true) {
            if (reservationData.selectedPackage === null) {
                alert("Please choose a package to experience before proceeding.");
                return;
            }
        } else if (reservationData.wantsPackage === false) {
            if (reservationData.customAttractions.length === 0) {
                alert("Please choose at least one attraction to experience before proceeding.");
                return; 
            }
        }

        // 2. Check Vehicles
        if (reservationData.selectedVehicle === null) {
            alert("Please choose a vehicle to ride (or select NONE) before proceeding.");
            return;
        }
    }

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
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

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

    for (let i = 1; i <= 12; i++) {
        let val = i < 10 ? "0" + i : i.toString();
        let div = document.createElement("div");
        div.className = `time-option hour-option ${val === selectedHour ? 'selected' : ''}`;
        div.innerText = val;
        div.dataset.val = val;
        div.dataset.type = "hour";
        hourCol.appendChild(div);
    }

    for (let i = 0; i <= 59; i++) {
        let val = i < 10 ? "0" + i : i.toString();
        let div = document.createElement("div");
        div.className = `time-option minute-option ${val === selectedMinute ? 'selected' : ''}`;
        div.innerText = val;
        div.dataset.val = val;
        div.dataset.type = "minute";
        minCol.appendChild(div);
    }

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

    timeSelectBtn.addEventListener("click", () => {
        const calendarPopup = document.getElementById("calendar-popup");
        if(calendarPopup) calendarPopup.classList.remove("show"); // Close calendar
        timeMenu.classList.toggle("show");
    });

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
        if(timeMenu) timeMenu.classList.remove("show");
        calendarPopup.classList.toggle("show");
    });

    calendarPopup.addEventListener("click", (e) => {
        const dayCell = e.target.closest('.calendar-day');
        
        if (dayCell) {
            const dayNumber = dayCell.querySelector('.day-number').innerText;
            const currentMonthYear = document.querySelector('.current-month').innerText;
            
            const [month, year] = currentMonthYear.split(' ');
            
            dateDisplay.innerText = `${month} ${dayNumber}, ${year}`;
            
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

// --- STEP 2: PACKAGE AND VEHICLE LOGIC ---

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
        
        reservationData.vehicleQuantity = 0;

        const allVehicles = document.querySelectorAll('.vehicle-card');
        allVehicles.forEach(v => v.classList.remove('selected-card'));
    } else {
        reservationData.selectedVehicle = vehicleName;
        
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
        
        reservationData.vehicleQuantity = 0;

        document.querySelectorAll('.custom-vehicle-card').forEach(v => v.classList.remove('selected-card'));
    } else {
        reservationData.selectedVehicle = vehicleName;
        
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

function updateVehicleCount(change, event) {
    event.stopPropagation(); 

    let newCount = reservationData.vehicleQuantity + change;

    if (newCount < 1) {
        newCount = 1;
    }

    reservationData.vehicleQuantity = newCount;

    const activeCard = event.target.closest('.vehicle-card') || event.target.closest('.custom-vehicle-card');
    if (activeCard) {
        activeCard.querySelector('.veh-count').innerText = newCount;
    }

    console.log("Vehicle Quantity updated:", reservationData.vehicleQuantity);
}

// ------------- STEP 3 LOGIC ------------- 

function submitReservation() {
    const name = document.getElementById('contact-name').value.trim();
    const email = document.getElementById('contact-email').value.trim();
    const phone = document.getElementById('contact-phone').value.trim();

    if (!name || !email || !phone) {
        alert("Please fill out all contact details.");
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.com$/i;
    
    if (!emailRegex.test(email)) {
        alert("Please enter a valid email address (e.g., yourname@email.com).");
        return;
    }

    reservationData.contactInfo = { name, email, phone };

    buildAndShowModal();
}