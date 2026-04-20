// --- MODAL LOGIC STEP 3 ---

function submitReservation() {
    const name = document.getElementById('contact-name').value.trim();
    const email = document.getElementById('contact-email').value.trim();
    const phone = document.getElementById('contact-phone').value.trim();

    if (!name || !email || !phone) {
        alert("Please fill out all contact details.");
        return;
    }

    reservationData.contactInfo = { name, email, phone };

    document.getElementById('modal-adults').innerText = reservationData.tourists.adults;
    document.getElementById('modal-children').innerText = reservationData.tourists.children;
    document.getElementById('modal-infants').innerText = reservationData.tourists.infants;
    
    const adultLabel = document.getElementById('modal-adult-label');
    if (reservationData.includesSeniors === true) {
        adultLabel.innerText = "ADULTS & SENIORS";
    } else {
        adultLabel.innerText = "ADULTS";
    }

    document.getElementById('modal-package').innerText = reservationData.wantsPackage ? (reservationData.selectedPackage || "YES") : "NO";
    
    document.getElementById('modal-vehicle').innerText = reservationData.selectedVehicle || "NONE";
    
    document.getElementById('modal-full-name').innerText = name;
    document.getElementById('modal-email').innerText = email;
    document.getElementById('modal-phone').innerText = phone;

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

    document.getElementById('confirmationModal').classList.add('show');


    document.getElementById('modal-package').innerText = reservationData.wantsPackage ? (reservationData.selectedPackage || "YES") : "NO";
    
    const vehicleDisplay = document.getElementById('modal-vehicle');
    const vehicleQuantityDisplay = document.getElementById('modal-vehicle-quantity');
    
    if (reservationData.selectedVehicle && reservationData.selectedVehicle !== 'None') {
        vehicleDisplay.innerText = reservationData.selectedVehicle;
        vehicleQuantityDisplay.innerText = reservationData.vehicleQuantity; 
    } else {
        vehicleDisplay.innerText = "NONE";
        vehicleQuantityDisplay.innerText = ""; 
    }
    
    document.getElementById('modal-full-name').innerText = name;

}

document.getElementById('closeModal').addEventListener('click', () => {
    document.getElementById('confirmationModal').classList.remove('show');
});

// Function for the final green ACCEPT button
function confirmFinalAcceptance() {
    alert("Thank you! Your reservation for RENTramuros has been submitted.");
    location.reload(); 
}

/* BACKEND HAND-OFF (Placeholder) */
function sendDataToDatabase(data) {
    const finalJSON = JSON.stringify(data, null, 2);
    console.log("FINAL JSON PAYLOAD:", finalJSON);
}