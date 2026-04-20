// ------------ TOURS MODAL RECEIPT ----------------

function buildAndShowModal() {
    
    document.getElementById('modal-adults').innerText = reservationData.tourists.adults;
    document.getElementById('modal-children').innerText = reservationData.tourists.children;
    document.getElementById('modal-infants').innerText = reservationData.tourists.infants;
    
    const adultLabel = document.getElementById('modal-adult-label');
    adultLabel.innerText = reservationData.includesSeniors ? "ADULTS & SENIORS" : "ADULTS";

    document.getElementById('modal-package').innerText = reservationData.wantsPackage ? (reservationData.selectedPackage || "YES") : "NO";

    const travelDate = document.getElementById('date-display').innerText;
    const travelTime = document.getElementById('time-display').innerText;
    document.getElementById('modal-date-time').innerText = `${travelDate} ; ${travelTime}`;

    const itineraryList = document.getElementById('modal-itinerary-list');
    itineraryList.innerHTML = ""; // Clear old list
    
    if (reservationData.customAttractions.length > 0) {
        reservationData.customAttractions.forEach(attr => {
            const li = document.createElement('li');
            li.innerText = `• ${attr}`;
            itineraryList.appendChild(li);
        });
    } else {
        itineraryList.innerHTML = "<li class='no-itinerary-text'>No custom attractions selected</li>";
    }

    const vehicleDisplay = document.getElementById('modal-vehicle');
    const vehicleQuantityDisplay = document.getElementById('modal-vehicle-quantity');
    
    if (reservationData.selectedVehicle && reservationData.selectedVehicle !== 'None') {
        vehicleDisplay.innerText = reservationData.selectedVehicle;
        vehicleQuantityDisplay.innerText = reservationData.vehicleQuantity; 
    } else {
        vehicleDisplay.innerText = ""; 
        vehicleQuantityDisplay.innerText = "NONE"; 
    }

    document.getElementById('modal-full-name').innerText = reservationData.contactInfo.name;
    document.getElementById('modal-email').innerText = reservationData.contactInfo.email;
    document.getElementById('modal-phone').innerText = reservationData.contactInfo.phone;

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