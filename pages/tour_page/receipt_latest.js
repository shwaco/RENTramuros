function buildAndShowModal() {
    
    const adults = parseInt(reservationData.tourists.adults) || 0;
    const children = parseInt(reservationData.tourists.children) || 0;
    const infants = parseInt(reservationData.tourists.infants) || 0;
    
    document.getElementById('modal-adults').innerText = adults;
    document.getElementById('modal-children').innerText = children;
    document.getElementById('modal-infants').innerText = infants;
    
    const adultLabel = document.getElementById('modal-adult-label');
    if(adultLabel) adultLabel.innerText = reservationData.includesSeniors ? "ADULTS & SENIORS" : "ADULTS";

    const pax = adults + children;
    const multiplier = pax > 0 ? pax : 1; 

    const travelDate = document.getElementById('date-display').innerText;
    const travelTime = document.getElementById('time-display').innerText;
    document.getElementById('modal-date-time').innerText = `${travelDate} ; ${travelTime}`;

    const isPackage = reservationData.wantsPackage;
    
    const pkgFee = parseFloat(reservationData.selectedPackagePrice) || 0;
    const totalPkgCost = pkgFee * multiplier;

    const vPrice = parseFloat(reservationData.selectedVehiclePrice) || 0;
    const vCount = parseInt(reservationData.vehicleQuantity) || 0;
    const vMultiplier = vCount > 0 ? vCount : 1;
    const totalVCost = vPrice * vMultiplier;

    let baseTotal = totalVCost;

    let pkgDisplay = isPackage ? (reservationData.selectedPackage || 'YES') : 'No Package';
    if (isPackage && totalPkgCost > 0) {
        pkgDisplay += `&nbsp;&nbsp;<span class="text-green">₱${totalPkgCost.toLocaleString('en-PH')}</span>`;
        baseTotal += totalPkgCost;
    }
    document.getElementById('modal-package').innerHTML = pkgDisplay;

    const itinContainer = document.getElementById('modal-itinerary-list');
    itinContainer.innerHTML = '';
    
    if (isPackage) {
        if (reservationData.selectedPackageItineraryIds && reservationData.selectedPackageItineraryIds.length > 0) {
            reservationData.selectedPackageItineraryIds.forEach(id => {
                const attrName = reservationData.attractionDictionary[id] || "Unknown Attraction";
                itinContainer.innerHTML += `<span>${attrName}</span>`; 
            });
        } else {
            itinContainer.innerHTML = '<span class="no-itinerary-text">No itinerary details available</span>';
        }
    } else {
        if (reservationData.customAttractions && reservationData.customAttractions.length > 0) {
            reservationData.customAttractions.forEach(attr => {
                const parts = attr.split('|');
                const name = parts[0] ? parts[0].trim() : '';
                const fee = parts[1] ? parseFloat(parts[1]) : 0; 
                
                const totalDestFee = fee * multiplier;
                
                if (totalDestFee > 0) {
                    baseTotal += totalDestFee; 
                    itinContainer.innerHTML += `<span>${name}&nbsp;&nbsp;<span class="text-green" style="font-size: 0.8rem;">₱${totalDestFee.toLocaleString('en-PH')}</span></span>`;
                } else {
                    itinContainer.innerHTML += `<span>${name}</span>`; 
                }
            });
        } else {
            itinContainer.innerHTML = '<span class="no-itinerary-text">No custom attractions selected</span>';
        }
    }

    let vNameDisplay = reservationData.selectedVehicle && reservationData.selectedVehicle !== 'None' ? reservationData.selectedVehicle : 'NONE';
    if (vNameDisplay !== 'NONE' && totalVCost > 0) {
        vNameDisplay += `&nbsp;&nbsp;<span class="text-green" style="font-size: 0.8rem;">₱${totalVCost.toLocaleString('en-PH')}</span>`;
    }
    document.getElementById('modal-vehicle').innerHTML = vNameDisplay;
    
    const vehicleQuantityEl = document.getElementById('modal-vehicle-quantity');
    if (vehicleQuantityEl) {
        vehicleQuantityEl.innerText = vNameDisplay !== 'NONE' ? vCount : "";
    }

    const minGrandTotal = baseTotal + 1000; 
    const maxGrandTotal = baseTotal + 1500; 

    document.getElementById('modal-base-fee').innerText = `₱${baseTotal.toLocaleString('en-PH')}`;
    document.getElementById('modal-grand-total').innerText = `₱${minGrandTotal.toLocaleString('en-PH')} - ₱${maxGrandTotal.toLocaleString('en-PH')}`;

    document.getElementById('modal-full-name').innerText = `${reservationData.contactInfo.firstName} ${reservationData.contactInfo.lastName}`;
    document.getElementById('modal-email').innerText = reservationData.contactInfo.email;
    document.getElementById('modal-phone').innerText = reservationData.contactInfo.phone;

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
        
        window.location.href = "../landing_page/landing_page.php"; 
        
    } else {
        alert("Server unreachable or database error. Make sure your local server (XAMPP) is running!");
        document.querySelector('.accept-btn').innerText = "SUBMIT";
        document.querySelector('.accept-btn').disabled = false;
    }
}