// ------------ TOURS MODAL RECEIPT ----------------

function buildAndShowModal() {
    
    // 1. TOURIST COUNTS & PAX LOGIC
    document.getElementById('modal-adults').innerText = reservationData.tourists.adults;
    document.getElementById('modal-children').innerText = reservationData.tourists.children;
    document.getElementById('modal-infants').innerText = reservationData.tourists.infants;
    
    const adultLabel = document.getElementById('modal-adult-label');
    adultLabel.innerText = reservationData.includesSeniors ? "ADULTS & SENIORS" : "ADULTS";

    const pax = reservationData.tourists.adults + reservationData.tourists.children;
    const paxMultiplier = pax > 0 ? pax : 1; 

    // 2. DATE & TIME
    const travelDate = document.getElementById('date-display').innerText;
    const travelTime = document.getElementById('time-display').innerText;
    document.getElementById('modal-date-time').innerText = `${travelDate} ; ${travelTime}`;

    // 3. THE NEW MATH ENGINE (Coworker's Rules Applied)
    let baseTotal = 0; 
    const itineraryList = document.getElementById('modal-itinerary-list');
    itineraryList.innerHTML = ""; 

    // --- A. Vehicle Math ---
    if (reservationData.selectedVehicle && reservationData.selectedVehicle !== 'None') {
        const vMultiplier = reservationData.vehicleQuantity > 0 ? reservationData.vehicleQuantity : 1;
        const totalVehicleCost = reservationData.selectedVehiclePrice * vMultiplier;
        baseTotal += totalVehicleCost;
        
        const formattedVCost = `₱${totalVehicleCost.toLocaleString('en-PH', { maximumFractionDigits: 0 })}`;
        document.getElementById('modal-vehicle').innerHTML = `${reservationData.selectedVehicle}&nbsp;&nbsp;<span style="color: #109620; font-weight: 700; font-style: italic; font-size: 0.85rem;">${formattedVCost}</span>`;
        document.getElementById('modal-vehicle-quantity').innerText = reservationData.vehicleQuantity; 
    } else {
        document.getElementById('modal-vehicle').innerText = "NONE"; 
        document.getElementById('modal-vehicle-quantity').innerText = ""; 
    }

    // --- B. Package vs Custom Math ---
    let packageDisplayString = "No Package";

    if (reservationData.wantsPackage) { 
        // IF PACKAGE: Flat calculation, hide itinerary prices
        packageDisplayString = reservationData.selectedPackage || "YES";
        const totalPackageCost = reservationData.selectedPackagePrice * paxMultiplier;
        baseTotal += totalPackageCost;
        
        if (totalPackageCost > 0) {
            packageDisplayString += `&nbsp;&nbsp;<span style="color: #109620; font-weight: 700; font-style: italic; font-size: 0.85rem;">₱${totalPackageCost.toLocaleString('en-PH', { maximumFractionDigits: 0 })}</span>`;
        }
        
        if (reservationData.selectedPackageItineraryIds && reservationData.selectedPackageItineraryIds.length > 0) {
            // Loop through the junction IDs and look up their names!
            reservationData.selectedPackageItineraryIds.forEach(id => {
                const attrName = reservationData.attractionDictionary[id] || "Unknown Attraction";
                itineraryList.innerHTML += `<span style="font-family: 'Roboto Condensed', sans-serif; font-size: 0.85rem;">${attrName}</span>`; 
            });
        } else {
            itineraryList.innerHTML = "<span class='no-itinerary-text' style='grid-column: span 3;'>No itinerary details available</span>";
        }
        
    } else {
        // IF CUSTOM: Calculate and show individual attraction prices
        if (reservationData.customAttractions.length > 0) {
            reservationData.customAttractions.forEach(attr => {
                const parts = attr.split('|');
                const name = parts[0] ? parts[0].trim() : '';
                const baseFee = parts[1] ? parseFloat(parts[1]) : 0; 
                
                const totalAttractionFee = baseFee * paxMultiplier;
                
                if (totalAttractionFee > 0) {
                    baseTotal += totalAttractionFee; 
                    const formattedFee = `₱${totalAttractionFee.toLocaleString('en-PH', { maximumFractionDigits: 0 })}`;
                    itineraryList.innerHTML += `<span style="font-family: 'Roboto Condensed', sans-serif; font-size: 0.85rem;">${name}&nbsp;&nbsp;<span style="color: #109620; font-weight: 700; font-style: italic; font-size: 0.9rem;">${formattedFee}</span></span>`;
                } else {
                    itineraryList.innerHTML += `<span style="font-family: 'Roboto Condensed', sans-serif; font-size: 0.85rem;">${name}</span>`; 
                }
            });
        } else {
            itineraryList.innerHTML = "<span class='no-itinerary-text' style='grid-column: span 3;'>No custom attractions selected</span>";
        }
    }

    document.getElementById('modal-package').innerHTML = packageDisplayString;

    // --- C. The 3-Tier Grand Total Output ---
    const minGrandTotal = baseTotal + 1000; 
    const maxGrandTotal = baseTotal + 1500; 

    document.getElementById('modal-base-fee').innerText = `₱${baseTotal.toLocaleString('en-PH', { maximumFractionDigits: 0 })}`;
    document.getElementById('modal-grand-total').innerText = `₱${minGrandTotal.toLocaleString('en-PH', { maximumFractionDigits: 0 })} - ₱${maxGrandTotal.toLocaleString('en-PH', { maximumFractionDigits: 0 })}`;

    // 4. CONTACT INFO
    document.getElementById('modal-full-name').innerText = `${reservationData.contactInfo.firstName} ${reservationData.contactInfo.lastName}`;
    document.getElementById('modal-email').innerText = reservationData.contactInfo.email;
    document.getElementById('modal-phone').innerText = reservationData.contactInfo.phone;

    // 5. SHOW MODAL
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
    
    // Pass the baton to the real database function
    sendDataToDatabase();
}

/* REAL BACKEND API HAND-OFF */
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

    // 2. Format the payload exactly to the FLAT structure your PHP expects
    const dbPayload = {
        tourist_id: 1, // Keeping this hardcoded so your local testing works!
        booking_type: reservationData.wantsPackage ? "Package" : "Attractions",
        booking_time: document.getElementById('time-display').innerText, // MATCHES SQL
        booking_date: document.getElementById('date-display').innerText, // MATCHES SQL
        adults_and_seniors: reservationData.tourists.adults, 
        children: reservationData.tourists.children,
        infants: reservationData.tourists.infants,
        package_id: finalPackageId,
        vehicle_id: finalVehicleId,
        number_of_vehicle: reservationData.vehicleQuantity, // MATCHES SQL (Singular)
        
        // Contact info
        first_name: reservationData.contactInfo.firstName,
        last_name: reservationData.contactInfo.lastName,
        email_address: reservationData.contactInfo.email,
        phone_number: reservationData.contactInfo.phone,
        
        // Attractions array
        attraction_id: finalAttractions
    };

    // 3. Keep the console log so you can still flex the data structure
    let displayJson = JSON.stringify(dbPayload, null, 2);
    displayJson = displayJson.replace(/"attraction_id": \[\s*([\s\S]*?)\s*\]/, function(match, innerText) {
        return '"attraction_id": [' + innerText.replace(/\s+/g, '') + ']';
    });
    console.log("SENDING EXACT PAYLOAD TO API:", displayJson);

    // 4. THE REAL API POST TO save_online_booking.php
    const isSuccess = await window.submitBookingRequest(dbPayload);

    // 5. Handle the UI based on the real database response
    if (isSuccess) {
        alert("Thank you! Your reservation for RENTramuros has been submitted.");
        location.reload(); 
    } else {
        alert("Server unreachable or database error. Make sure your local server (XAMPP) is running!");
        document.querySelector('.accept-btn').innerText = "SUBMIT";
        document.querySelector('.accept-btn').disabled = false;
    }
}