// --- MASTER RESERVATION DATA RECORD ---
let reservationData = {
    wantsPackage: null, 
    selectedPackage: null, 
    selectedPackageId: null, // NEW: Tracks package_id
    selectedVehicle: null,
    selectedVehicleId: null, // NEW: Tracks vehicle_id
    vehicleQuantity: 0,
    customAttractions: [], 
    customAttractionIds: [], // NEW: Tracks array for REQUEST_ATTRACTIONS junction table
    tourists: {
        adults: 2,
        children: 0,
        infants: 0
    },
    includesSeniors: false,
    
    contactInfo: {
        firstName: "",
        lastName: "",
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

// --- DATE CALENDAR POPUP LOGIC ---
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

// --- TOURIST COUNTER LOGIC ---
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

// --- SENIOR CHECKBOX LOGIC ---
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

// --- STEP 2: PACKAGE AND VEHICLE LOGIC ---

// --- YES/NO PACKAGE BUTTON LOGIC ---
function selectPackageOption(wantsPackage) {
    reservationData.wantsPackage = wantsPackage;
    
    const btnYes = document.getElementById('btn-yes');
    const btnNo = document.getElementById('btn-no');
    
    btnYes.classList.remove('active-selection');
    btnNo.classList.remove('active-selection');

    if (wantsPackage) {
        btnYes.classList.add('active-selection');
    } else {
        btnNo.classList.add('active-selection');
    }
    
    console.log("Current Data:", reservationData);
}

// --- FULLY DYNAMIC SELECTION LOGIC ---
// --- PACKAGE LOGIC ---
function selectPackage(packageId, packageName) {
    if (reservationData.selectedPackage === packageName) {
        reservationData.selectedPackage = null; 
        reservationData.selectedPackageId = null; // Clear ID
        document.querySelectorAll('.package-options-container > div').forEach(p => p.classList.remove('selected-card'));
    } else {
        reservationData.selectedPackage = packageName;
        reservationData.selectedPackageId = packageId; // Save ID
        document.querySelectorAll('.package-options-container > div').forEach(p => p.classList.remove('selected-card'));
        document.getElementById(packageId).classList.add('selected-card');
    }
}

// --- VEHICLE LOGIC ---
function selectVehicle(vehicleId, vehicleName) {
    if (reservationData.selectedVehicle === vehicleName) {
        reservationData.selectedVehicle = null;
        reservationData.selectedVehicleId = null; // Clear ID
        reservationData.vehicleQuantity = 0;
        document.querySelectorAll('#dynamic-package-vehicles .vehicle-card').forEach(v => v.classList.remove('selected-card'));
    } else {
        reservationData.selectedVehicle = vehicleName;
        reservationData.selectedVehicleId = vehicleId; // Save ID
        reservationData.vehicleQuantity = 1;
        document.querySelectorAll('#dynamic-package-vehicles .veh-count').forEach(el => el.innerText = '1');

        document.querySelectorAll('#dynamic-package-vehicles .vehicle-card').forEach(v => v.classList.remove('selected-card'));
        document.getElementById(vehicleId).classList.add('selected-card');
    }
}

function selectCustomVehicle(vehicleId, vehicleName) {
    if (reservationData.selectedVehicle === vehicleName) {
        reservationData.selectedVehicle = null;
        reservationData.selectedVehicleId = null; // Clear ID
        reservationData.vehicleQuantity = 0;
        document.querySelectorAll('#dynamic-custom-vehicles .custom-vehicle-card').forEach(v => v.classList.remove('selected-card'));
    } else {
        reservationData.selectedVehicle = vehicleName;
        reservationData.selectedVehicleId = vehicleId; // Save ID
        reservationData.vehicleQuantity = 1;
        document.querySelectorAll('#dynamic-custom-vehicles .veh-count').forEach(el => el.innerText = '1');

        document.querySelectorAll('#dynamic-custom-vehicles .custom-vehicle-card').forEach(v => v.classList.remove('selected-card'));
        document.getElementById(vehicleId).classList.add('selected-card');
    }
}

// --- STEP 2 CUSTOM LOGIC ---
// UPDATED FOR THE JUNCTION TABLE
 function toggleAttraction(attractionName, elementId) {
    const array = reservationData.customAttractions;
    const idArray = reservationData.customAttractionIds; // Connects to the new ID array
    const index = array.indexOf(attractionName);
    const card = document.getElementById(elementId);

    if (index > -1) {
        array.splice(index, 1);
        idArray.splice(index, 1); // Remove ID if unselected
        card.classList.remove('selected-card');
    } else {
        array.push(attractionName);
        idArray.push(elementId); // Add ID if selected
        card.classList.add('selected-card');
    }
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
    // Grab the new separate IDs
    const firstName = document.getElementById('contact-first-name').value.trim();
    const lastName = document.getElementById('contact-last-name').value.trim();
    const email = document.getElementById('contact-email').value.trim();
    const phone = document.getElementById('contact-phone').value.trim();

    // Check all 4
    if (!firstName || !lastName || !email || !phone) {
        alert("Please fill out all contact details.");
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.com$/i;
    
    if (!emailRegex.test(email)) {
        alert("Please enter a valid email address (e.g., yourname@email.com).");
        return;
    }

    // Save them to the brain
    reservationData.contactInfo = { firstName, lastName, email, phone };

    buildAndShowModal();
}

// --- DYNAMIC RENDERER LOGIC ---

async function initDynamicTours() {
    // Calls the function from tours-api.js
    const data = await fetchToursData();
    
    if (data) {
        renderPackages(data.packages);
        renderVehicles(data.vehicles);
        renderAttractions(data.attractions);
    }
}

function renderPackages(packages) {
    const container = document.getElementById('dynamic-packages');
    let html = '';

    packages.forEach((pkg, index) => {
        // Now passes both ID and Name to selectPackage
        html += `
        <div class="package-${index + 1}" id="${pkg.id}" onclick="selectPackage('${pkg.id}', '${pkg.name}')">
            <div class="package-image"><img src="${pkg.image}" alt="${pkg.name}"></div>
            <div class="package-details-text">
                <span class="package-label">${pkg.name}</span>
                <span class="package-${index + 1}-inclusion">${pkg.inclusions}</span>
                <span class="package-${index + 1}-price">${pkg.price}</span>
            </div>
        </div>`;
    });

    container.innerHTML = html;
}

function renderVehicles(vehicles) {
    const pkgVehiclesContainer = document.getElementById('dynamic-package-vehicles');
    const customVehiclesContainer = document.getElementById('dynamic-custom-vehicles');

    let pkgHtml = '';
    let customHtml = '';

    vehicles.forEach((veh, index) => {
        const capacityClass = (index === 2) ? 'vehicle-3-capacity' : 'vehicle-capacity';

        // Regular Package Vehicles - added veh.id
        pkgHtml += `
        <div class="vehicle-${index + 1} vehicle-card" id="${veh.id}" onclick="selectVehicle('${veh.id}', '${veh.name}')">
            <div class="vehicle-counter">
                <button type="button" class="veh-minus" onclick="updateVehicleCount(-1, event)">-</button>
                <span class="veh-count">1</span>
                <button type="button" class="veh-plus" onclick="updateVehicleCount(1, event)">+</button>
            </div>
            <img src="${veh.image}" alt="${veh.name}">
            <div class="vehicle-overlay">
                <span class="vehicle-name">${veh.name}</span>
                <span class="${capacityClass}">${veh.capacity}</span>
            </div>
        </div>`;

        // Custom Vehicles - added custom-${veh.id}
        customHtml += `
        <div class="custom-vehicle-${index + 1} custom-vehicle-card" id="custom-${veh.id}" onclick="selectCustomVehicle('custom-${veh.id}', '${veh.name}')">
            <div class="vehicle-counter">
                <button type="button" class="veh-minus" onclick="updateVehicleCount(-1, event)">-</button>
                <span class="veh-count">1</span>
                <button type="button" class="veh-plus" onclick="updateVehicleCount(1, event)">+</button>
            </div>
            <img src="${veh.image}" alt="${veh.name}">
            <div class="vehicle-overlay">
                <span class="vehicle-name">${veh.name}</span>
                <span class="${capacityClass}">${veh.capacity}</span>
            </div>
        </div>`;
    });

    pkgVehiclesContainer.innerHTML += pkgHtml;
    customVehiclesContainer.innerHTML += customHtml;
}

function renderAttractions(attractions) {
    const layer1 = document.getElementById('dynamic-attractions-layer-1');
    const layer2 = document.getElementById('dynamic-attractions-layer-2');

    let layer1Html = '';
    let layer2Html = '';

    attractions.forEach((attr, index) => {
        // 1. Grab the fee from your JSON
        const fee = attr.fee || 0;
        
        // 2. Build the string
        const dataString = `${attr.name} | ${fee}`;

        // THE FIX: This finds any apostrophes in names like "Rizal's" and safely escapes them!
        const safeDataString = dataString.replace(/'/g, "\\'");

        // 3. Pass safeDataString into toggleAttraction
        const cardHtml = `
        <div class="attraction-${index + 1} attraction-card" id="${attr.id}" onclick="toggleAttraction('${safeDataString}', '${attr.id}')">
            <img src="${attr.image}" alt="${attr.name}">
        </div>`;

        if (index < 5) {
            layer1Html += cardHtml;
        } else {
            layer2Html += cardHtml;
        }
    });

    layer1.innerHTML = layer1Html;
    layer2.innerHTML = layer2Html;
}

// Fire it up when the DOM loads
document.addEventListener('DOMContentLoaded', initDynamicTours);