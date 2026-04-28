// ------------ TOURS MODAL RECEIPT ----------------

function buildAndShowModal() {
    
    // 1. TOURIST COUNTS
    document.getElementById('modal-adults').innerText = reservationData.tourists.adults;
    document.getElementById('modal-children').innerText = reservationData.tourists.children;
    document.getElementById('modal-infants').innerText = reservationData.tourists.infants;
    
    const adultLabel = document.getElementById('modal-adult-label');
    adultLabel.innerText = reservationData.includesSeniors ? "ADULTS & SENIORS" : "ADULTS";

    // 2. PACKAGE & DATE
    document.getElementById('modal-package').innerText = reservationData.wantsPackage ? (reservationData.selectedPackage || "YES") : "NO";
    const travelDate = document.getElementById('date-display').innerText;
    const travelTime = document.getElementById('time-display').innerText;
    document.getElementById('modal-date-time').innerText = `${travelDate} ; ${travelTime}`;

    // 3. ATTRACTIONS & FEE CALCULATION (The new logic!)
    const itineraryList = document.getElementById('modal-itinerary-list');
    itineraryList.innerHTML = ""; // Clear old list
    let totalFee = 0; // Initialize total
    
    if (reservationData.customAttractions.length > 0) {
        reservationData.customAttractions.forEach(attr => {
            const parts = attr.split('|');
            const name = parts[0] ? parts[0].trim() : '';
            
            // CHANGED: Using parseFloat to keep the decimals from the database!
            const fee = parts[1] ? parseFloat(parts[1]) : 0;
            
            if (fee > 0) totalFee += fee;

            const li = document.createElement('li');
            if (fee > 0) {
                // Formats the individual item fee with 2 decimal places
                const formattedFee = `₱${fee.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                li.innerHTML = `• ${name}&nbsp;&nbsp;<span style="color: #109620; font-weight: 600; font-style: italic; font-size: 0.8rem;">${formattedFee}</span>`;
            } else {
                li.innerHTML = `• ${name}`;
            }
            itineraryList.appendChild(li);
        });
    } else {
        itineraryList.innerHTML = "<li class='no-itinerary-text'>No custom attractions selected</li>";
    }

    // Display the Total Fee using your colleague's updated formatting (No decimals)
    const feeDisplay = totalFee > 0 
        ? `₱${totalFee.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` 
        : '₱0.00';
    document.getElementById('modal-total-fee').innerText = feeDisplay;  

    // 4. VEHICLE
    const vehicleDisplay = document.getElementById('modal-vehicle');
    const vehicleQuantityDisplay = document.getElementById('modal-vehicle-quantity');
    
    if (reservationData.selectedVehicle && reservationData.selectedVehicle !== 'None') {
        vehicleDisplay.innerText = reservationData.selectedVehicle;
        vehicleQuantityDisplay.innerText = reservationData.vehicleQuantity; 
    } else {
        vehicleDisplay.innerText = ""; 
        vehicleQuantityDisplay.innerText = "NONE"; 
    }

    // 5. CONTACT INFO
    document.getElementById('modal-full-name').innerText = `${reservationData.contactInfo.firstName} ${reservationData.contactInfo.lastName}`;
    
    document.getElementById('modal-email').innerText = reservationData.contactInfo.email;
    document.getElementById('modal-phone').innerText = reservationData.contactInfo.phone;

    // 6. SHOW MODAL
    document.getElementById('confirmationModal').classList.add('show');
}

// FINAL ACTIONS (Close & Accept)
document.getElementById('closeModal').addEventListener('click', () => {
    document.getElementById('confirmationModal').classList.remove('show');
});

function confirmFinalAcceptance() {
    // UX feedback
    const acceptBtn = document.querySelector('.accept-btn');
    acceptBtn.innerText = "PROCESSING...";
    acceptBtn.disabled = true;
    
    sendDataToDatabase();
}

/* BACKEND API HAND-OFF */
async function sendDataToDatabase() {
    
    // 1. IDs are already pure numbers from the JSON, no cleanup needed!
    let finalVehicleId = null;
    if (reservationData.selectedVehicleId && reservationData.selectedVehicleId !== 'veh-none' && reservationData.selectedVehicleId !== 'custom-veh-none') {
        finalVehicleId = reservationData.selectedVehicleId; 
    }

    let finalPackageId = null;
    if (reservationData.wantsPackage && reservationData.selectedPackageId) {
        finalPackageId = reservationData.selectedPackageId; 
    }

    let finalAttractions = [];
    if (!reservationData.wantsPackage) {
        finalAttractions = reservationData.customAttractionIds;
    }

    // 2. Format the payload exactly to the new FLAT structure requested
    const dbPayload = {
        tourist_id: 1, // Placeholder (matches coworker's image)
        booking_type: reservationData.wantsPackage ? "Package" : "Attractions",
        
        // Note: I kept time and date here because the database will still need them!
        time: document.getElementById('time-display').innerText,
        date: document.getElementById('date-display').innerText,
        
        adults_and_seniors: reservationData.tourists.adults, 
        children: reservationData.tourists.children,
        infants: reservationData.tourists.infants,
        package_id: finalPackageId,
        vehicle_id: finalVehicleId,
        number_of_vehicles: reservationData.vehicleQuantity,
        
        first_name: reservationData.contactInfo.firstName,
        last_name: reservationData.contactInfo.lastName,
        email_address: reservationData.contactInfo.email,
        phone_number: reservationData.contactInfo.phone,
        
        attraction_id: finalAttractions
    };

    let displayJson = JSON.stringify(dbPayload, null, 2);
    
    // Use a quick trick to flatten ONLY the attraction_id array into one line
    displayJson = displayJson.replace(/"attraction_id": \[\s*([\s\S]*?)\s*\]/, function(match, innerText) {
        return '"attraction_id": [' + innerText.replace(/\s+/g, '') + ']';
    });

    console.log("SENDING EXACT PAYLOAD TO API:", displayJson);

    // 3. Call the API function from our separated file
    const isSuccess = await window.submitBookingRequest(dbPayload);

    // 4. Handle the UI based on the API response
    if (isSuccess) {
        alert("Thank you! Your reservation for RENTramuros has been submitted.");
        location.reload(); 
    } else {
        alert("Server unreachable or database error. Check console (F12) to view the payload.");
        document.querySelector('.accept-btn').innerText = "SUBMIT";
        document.querySelector('.accept-btn').disabled = false;
    }
}