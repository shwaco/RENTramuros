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
        alert("Server unreachable or database error. Make sure your local server is running!");
        document.querySelector('.accept-btn').innerText = "SUBMIT";
        document.querySelector('.accept-btn').disabled = false;
    }
}