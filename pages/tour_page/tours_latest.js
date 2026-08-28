// --- DYNAMIC RENDERER LOGIC ---

async function initDynamicTours() {
    // Calls the function from tours-api.js
    const data = await fetchToursData();
    
    console.log("Here is the raw data from PHP:", data); 
    
    if (data) {
        renderAttractions(data.attractions); 
        renderPackages(data.packages);
        renderVehicles(data.vehicles);
    }
}

function renderPackages(packages) {
    const container = document.getElementById('dynamic-packages');
    let html = '';

    packages.forEach((pkg, index) => {
        let itineraryListHtml = '';
        if (pkg.itinerary_ids && pkg.itinerary_ids.length > 0) {
            pkg.itinerary_ids.forEach(id => {
                const attrName = reservationData.attractionDictionary[id] || "Unknown Attraction";
                itineraryListHtml += `- ${attrName}<br>`;
            });
        }

        const numericPrice = parseFloat(pkg.price);
        const displayPrice = isNaN(numericPrice) ? '₱0' : `₱${numericPrice.toLocaleString('en-PH', { maximumFractionDigits: 0 })}`;

        html += `
        <div class="package-${index + 1}" id="pkg-${pkg.package_id}" data-name="${pkg.package_name}" data-desc="${pkg.description}" data-itinerary="[${pkg.itinerary_ids}]" onclick="selectPackage(${pkg.package_id}, this.dataset.name, ${pkg.price}, this.dataset.desc, this.dataset.itinerary)">
            <div class="package-image">
                <img src="../../asset/img/${pkg.image_file}" alt="${pkg.package_name}">
            </div>
            <div class="package-details-text">
                <span class="package-label">${pkg.package_name}</span>
                <span class="package-description">${itineraryListHtml}</span>
                <span class="package-price">${displayPrice}</span>
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

        const imgPath = `../../asset/img/${veh.image_file}`;

        pkgHtml += `
        <div class="vehicle-${index + 1} vehicle-card" id="veh-${veh.vehicle_id}" data-name="${veh.vehicle_type}" onclick="selectVehicle(${veh.vehicle_id}, this.dataset.name, ${veh.price})">
            <div class="vehicle-counter">
                <button type="button" class="veh-minus" onclick="updateVehicleCount(-1, event)">-</button>
                <span class="veh-count">1</span>
                <button type="button" class="veh-plus" onclick="updateVehicleCount(1, event)">+</button>
            </div>
            <img src="${imgPath}" alt="${veh.vehicle_type}">
            <div class="vehicle-overlay">
                <span class="vehicle-name">${veh.vehicle_type}</span>
                <span class="${capacityClass}">${veh.passenger_capacity}</span>
            </div>
        </div>`;

        customHtml += `
        <div class="custom-vehicle-${index + 1} custom-vehicle-card" id="custom-veh-${veh.vehicle_id}" data-name="${veh.vehicle_type}" onclick="selectCustomVehicle(${veh.vehicle_id}, this.dataset.name, ${veh.price})">
            <div class="vehicle-counter">
                <button type="button" class="veh-minus" onclick="updateVehicleCount(-1, event)">-</button>
                <span class="veh-count">1</span>
                <button type="button" class="veh-plus" onclick="updateVehicleCount(1, event)">+</button>
            </div>
            <img src="${imgPath}" alt="${veh.vehicle_type}">
            <div class="vehicle-overlay">
                <span class="vehicle-name">${veh.vehicle_type}</span>
                <span class="${capacityClass}">${veh.passenger_capacity}</span>
            </div>
        </div>`;
    });
    pkgVehiclesContainer.innerHTML += pkgHtml;
    customVehiclesContainer.innerHTML += customHtml;
}

function renderAttractions(attractions) {
    const container = document.getElementById('dynamic-attractions-container');
    let html = '';

    attractions.forEach((attr, index) => {
        const fee = attr.fee || 0;
        reservationData.attractionFees[attr.attraction_name] = fee; 
        reservationData.attractionDictionary[attr.attraction_id] = attr.attraction_name;
        
        const displayFee = fee > 0 ? `₱${fee.toLocaleString('en-PH', { maximumFractionDigits: 0 })}/head` : "FREE";

        html += `
        <div class="attraction-${index + 1} attraction-card" id="attr-${attr.attraction_id}" data-val="${attr.attraction_name} | ${fee}" onclick="toggleAttraction(this.dataset.val, ${attr.attraction_id})">
            <img src="../../asset/img/${attr.main_img}" alt="${attr.attraction_name}">
            <span class="attraction-price-pill">${displayFee}</span>
            <div class="attraction-name-overlay">
                <span class="attraction-name-label">${attr.attraction_name}</span>
            </div>
        </div>`;
    });
    container.innerHTML = html;
}

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
function selectPackage(packageId, packageName, packagePrice, packageDesc, itineraryString) { 
    if (reservationData.selectedPackage === packageName) {
        reservationData.selectedPackage = null; 
        reservationData.selectedPackageId = null; 
        reservationData.selectedPackagePrice = 0; 
        reservationData.selectedPackageDesc = ""; 
        reservationData.selectedPackageItineraryIds = []; // RESET
        document.querySelectorAll('.package-options-container > div').forEach(p => p.classList.remove('selected-card'));
    } else {
        reservationData.selectedPackage = packageName;
        reservationData.selectedPackageId = packageId; 
        reservationData.selectedPackagePrice = parseFloat(packagePrice) || 0; 
        reservationData.selectedPackageDesc = packageDesc || ""; 
        
        // Convert the string back into an array of numbers
        reservationData.selectedPackageItineraryIds = itineraryString ? JSON.parse(itineraryString) : []; 

        document.querySelectorAll('.package-options-container > div').forEach(p => p.classList.remove('selected-card'));
        document.getElementById(`pkg-${packageId}`).classList.add('selected-card'); 
    }
}

// --- VEHICLE LOGIC ---
function selectVehicle(vehicleId, vehicleName, vehiclePrice) { // ADDED PRICE HERE
    if (reservationData.selectedVehicle === vehicleName) {
        reservationData.selectedVehicle = null;
        reservationData.selectedVehicleId = null;
        reservationData.selectedVehiclePrice = 0; // RESET PRICE
        reservationData.vehicleQuantity = 0;
        document.querySelectorAll('#dynamic-package-vehicles .vehicle-card').forEach(v => v.classList.remove('selected-card'));
    } else {
        reservationData.selectedVehicle = vehicleName;
        reservationData.selectedVehicleId = vehicleId; 
        reservationData.selectedVehiclePrice = parseFloat(vehiclePrice) || 0; // SAVE PRICE
        reservationData.vehicleQuantity = 1;
        document.querySelectorAll('#dynamic-package-vehicles .veh-count').forEach(el => el.innerText = '1');

        document.querySelectorAll('#dynamic-package-vehicles .vehicle-card').forEach(v => v.classList.remove('selected-card'));
        const targetId = vehicleId === 'veh-none' ? 'veh-none' : `veh-${vehicleId}`;
        document.getElementById(targetId).classList.add('selected-card');
    }
}

function selectCustomVehicle(vehicleId, vehicleName, vehiclePrice) { // ADDED PRICE HERE
    if (reservationData.selectedVehicle === vehicleName) {
        // Same reset logic as above...
        reservationData.selectedVehicle = null;
        reservationData.selectedVehicleId = null; 
        reservationData.selectedVehiclePrice = 0;
        reservationData.vehicleQuantity = 0;
        document.querySelectorAll('#dynamic-custom-vehicles .custom-vehicle-card').forEach(v => v.classList.remove('selected-card'));
    } else {
        // Same save logic as above...
        reservationData.selectedVehicle = vehicleName;
        reservationData.selectedVehicleId = vehicleId; 
        reservationData.selectedVehiclePrice = parseFloat(vehiclePrice) || 0;
        reservationData.vehicleQuantity = 1;
        document.querySelectorAll('#dynamic-custom-vehicles .veh-count').forEach(el => el.innerText = '1');

        document.querySelectorAll('#dynamic-custom-vehicles .custom-vehicle-card').forEach(v => v.classList.remove('selected-card'));
        const targetId = vehicleId === 'custom-veh-none' ? 'custom-veh-none' : `custom-veh-${vehicleId}`;
        document.getElementById(targetId).classList.add('selected-card');
    }
}

// --- STEP 2 CUSTOM LOGIC ---
// UPDATED FOR THE JUNCTION TABLE
function toggleAttraction(attractionName, rawId) {
    const array = reservationData.customAttractions;
    const idArray = reservationData.customAttractionIds; 
    const index = array.indexOf(attractionName);
    const card = document.getElementById(`attr-${rawId}`); 

    if (index > -1) {
        array.splice(index, 1);
        idArray.splice(index, 1); 
        card.classList.remove('selected-card');
    } else {
        array.push(attractionName);
        idArray.push(rawId); // Stores the pure number!
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


// Fire it up when the DOM loads
document.addEventListener('DOMContentLoaded', initDynamicTours);