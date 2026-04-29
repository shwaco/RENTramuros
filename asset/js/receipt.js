// ------------ TOURS MODAL RECEIPT ----------------

function buildAndShowModal() {
    
    // 1. TOURIST COUNTS & PAX LOGIC
    document.getElementById('modal-adults').innerText = reservationData.tourists.adults;
    document.getElementById('modal-children').innerText = reservationData.tourists.children;
    document.getElementById('modal-infants').innerText = reservationData.tourists.infants;
    
    const adultLabel = document.getElementById('modal-adult-label');
    adultLabel.innerText = reservationData.includesSeniors ? "ADULTS & SENIORS" : "ADULTS";

    // Calculate Pax (Adults + Children) just like the backend
    const pax = reservationData.tourists.adults + reservationData.tourists.children;
    const paxMultiplier = pax > 0 ? pax : 1; // Failsafe

    // 2. PACKAGE & DATE
    document.getElementById('modal-package').innerText = reservationData.wantsPackage ? (reservationData.selectedPackage || "YES") : "NO PACKAGE";
    const travelDate = document.getElementById('date-display').innerText;
    const travelTime = document.getElementById('time-display').innerText;
    document.getElementById('modal-date-time').innerText = `${travelDate} ; ${travelTime}`;

    // 3. THE NEW MATH (Base Fee Calculation)
    let baseTotal = 0; 
    const itineraryList = document.getElementById('modal-itinerary-list');
    itineraryList.innerHTML = ""; 

    // --- A. Vehicle Math ---
    if (reservationData.selectedVehicle && reservationData.selectedVehicle !== 'None') {
        const vMultiplier = reservationData.vehicleQuantity > 0 ? reservationData.vehicleQuantity : 1;
        baseTotal += (reservationData.selectedVehiclePrice * vMultiplier);
    }

// --- B. Package or Attraction Math ---
    if (reservationData.wantsPackage) { 
        // We calculate it dynamically from the attractions inside it!
        
        if (reservationData.selectedPackageDesc) {
            const items = reservationData.selectedPackageDesc.split('\n');
            items.forEach(item => {
                const cleanName = item.replace(/^- /, '').trim();
                if (cleanName) {
                    
                    // 1. Look up the fee in our master dictionary (defaults to 0 if not found)
                    const baseFee = reservationData.attractionFees[cleanName] || 0;
                    
                    // 2. Multiply by the pax (adults + children)
                    const totalFee = baseFee * paxMultiplier;
                    baseTotal += totalFee; // Add it to the grand total
                    
                    // 3. Render it beautifully!
                    if (totalFee > 0) {
                        const formattedFee = `₱${totalFee.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                        itineraryList.innerHTML += `<span>${cleanName}&nbsp;&nbsp;<span style="color: #109620; font-weight: 600; font-style: italic; font-size: 0.8rem;">${formattedFee}</span></span>`;
                    } else {
                        itineraryList.innerHTML += `<span>${cleanName}</span>`; 
                    }
                }
            });
        } else {
            itineraryList.innerHTML = "<span class='no-itinerary-text' style='grid-column: span 3;'>No itinerary details available</span>";
        }
        
    } else {
        if (reservationData.customAttractions.length > 0) {
            reservationData.customAttractions.forEach(attr => {
                const parts = attr.split('|');
                const name = parts[0] ? parts[0].trim() : '';
                const baseFee = parts[1] ? parseFloat(parts[1]) : 0; // Renamed for clarity
                
                // NEW: Calculate the multiplied total for this specific attraction
                const totalAttractionFee = baseFee * paxMultiplier;
                
                if (totalAttractionFee > 0) {
                    baseTotal += totalAttractionFee; // Add to grand total
                    
                    // Display the MULTIPLIED total (e.g., 400), not the raw base fee (e.g., 100)!
                    const formattedFee = `₱${totalAttractionFee.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    itineraryList.innerHTML += `<span>${name}&nbsp;&nbsp;<span style="color: #109620; font-weight: 600; font-style: italic; font-size: 0.8rem;">${formattedFee}</span></span>`;
                } else {
                    itineraryList.innerHTML += `<span>${name}</span>`; 
                }
            });
        } else {
            itineraryList.innerHTML = "<span class='no-itinerary-text' style='grid-column: span 3;'>No custom attractions selected</span>";
        }
    }

    // --- C. Calculate the Guide Fee Range (The new final output!) ---
    let feeDisplay = '₱0.00';
    if (baseTotal > 0) {
        const minTotal = baseTotal + 1000; 
        const maxTotal = baseTotal + 1500; 

        const formattedMin = minTotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const formattedMax = maxTotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        feeDisplay = `₱${formattedMin} - ₱${formattedMax}`;
    }
    document.getElementById('modal-total-fee').innerText = feeDisplay;  

    // 4. VEHICLE DISPLAY
    const vehicleDisplay = document.getElementById('modal-vehicle');
    const vehicleQuantityDisplay = document.getElementById('modal-vehicle-quantity');
    
    if (reservationData.selectedVehicle && reservationData.selectedVehicle !== 'None') {
        const vMultiplier = reservationData.vehicleQuantity > 0 ? reservationData.vehicleQuantity : 1;
        const totalVehicleCost = reservationData.selectedVehiclePrice * vMultiplier;
        
        const formattedVehicleCost = `₱${totalVehicleCost.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        vehicleDisplay.innerHTML = `${reservationData.selectedVehicle}&nbsp;&nbsp;<span style="color: #109620; font-weight: 600; font-style: italic; font-size: 0.8rem;">${formattedVehicleCost}</span>`;
        vehicleQuantityDisplay.innerText = reservationData.vehicleQuantity; 
    } else {
        vehicleDisplay.innerText = "NONE"; 
        vehicleQuantityDisplay.innerText = ""; 
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