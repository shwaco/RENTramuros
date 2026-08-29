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
    
    sendDataToDatabase();
}

