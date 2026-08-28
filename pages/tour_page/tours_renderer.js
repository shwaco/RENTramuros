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

// Fire it up when the DOM loads
document.addEventListener('DOMContentLoaded', initDynamicTours);