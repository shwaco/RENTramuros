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
            // Your custom parsing logic: splits "Fort Santiago | 50"
            const parts = attr.split('|');
            const name = parts[0] ? parts[0].trim() : '';
            const fee = parts[1] ? parseInt(parts[1], 10) : 0;
            
            if (fee > 0) totalFee += fee;

            const li = document.createElement('li');
            if (fee > 0) {
                // Formats the item with the green price tag
                li.innerHTML = `• ${name}&nbsp;&nbsp;<span style="color: #109620; font-weight: 600; font-style: italic; font-size: 0.8rem;">₱${fee}</span>`;
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
        ? `₱${totalFee.toLocaleString('en-PH', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}` 
        : '₱0';
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
    alert("Thank you! Your reservation for RENTramuros has been submitted.");
    location.reload(); 
}

/* BACKEND HAND-OFF (Placeholder) */
function sendDataToDatabase(data) {
    const finalJSON = JSON.stringify(data, null, 2);
    console.log("FINAL JSON PAYLOAD:", finalJSON);
}   