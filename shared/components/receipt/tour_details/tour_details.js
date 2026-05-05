function renderTourDetails(tourData) {
    const adults = parseInt(tourData.adults_and_seniors) || 0;
    const children = parseInt(tourData.children) || 0;
    const pax = adults + children;
    const multiplier = pax > 0 ? pax : 1;

    const isPackage = tourData.package_name && tourData.package_name !== 'No Package';
    const pkgFee = parseFloat(tourData.package_price) || 0;
    const totalPkgCost = pkgFee * multiplier;
    
    const vPrice = parseFloat(tourData.vehicle_price) || 0;
    const vCount = parseInt(tourData.number_of_vehicle) || 0;
    const vMultiplier = vCount > 0 ? vCount : 1;
    const totalVCost = vPrice * vMultiplier;

    let baseTotal = totalVCost;

    let pkgDisplay = tourData.package_name || 'No Package';
    if (isPackage && totalPkgCost > 0) {
        pkgDisplay += `&nbsp;&nbsp;<span class="text-green">₱${totalPkgCost.toLocaleString('en-PH')}</span>`;
        baseTotal += totalPkgCost;
    }
    document.getElementById('js-package-display').innerHTML = pkgDisplay;

    const itinContainer = document.getElementById('js-itinerary-container');
    if (!tourData.destinations || tourData.destinations.trim() === "") {
        itinContainer.innerHTML = '<span>No Custom Attractions Selected</span>';
    } else {
        const destinationList = tourData.destinations.split(',');
        let itinHTML = '';
        destinationList.forEach(dest => {
            const parts = dest.trim().split('|');
            const name = parts[0] || '';
            const fee = parseFloat(parts[1]) || 0;
            const totalDestFee = fee * multiplier;

            if (totalDestFee > 0 && !isPackage) {
                baseTotal += totalDestFee;
                itinHTML += `<span>${name}&nbsp;&nbsp;<span class="text-green" style="font-size: 0.8rem;">₱${totalDestFee.toLocaleString('en-PH')}</span></span>`;
            } else {
                itinHTML += `<span>${name}</span>`;
            }
        });
        itinContainer.innerHTML = itinHTML;
    }

    let vNameDisplay = tourData.vehicle_type || 'NONE';
    if (totalVCost > 0) {
        vNameDisplay += `&nbsp;&nbsp;<span class="text-green" style="font-size: 0.8rem;">₱${totalVCost.toLocaleString('en-PH')}</span>`;
    }
    document.getElementById('js-vehicle-name').innerHTML = vNameDisplay;

    const minGrandTotal = baseTotal + 1000;
    const maxGrandTotal = baseTotal + 1500;

    document.getElementById('js-total-fee').innerText = "₱" + baseTotal.toLocaleString('en-PH');
    document.getElementById('js-grand-total').innerText = `₱${minGrandTotal.toLocaleString('en-PH')} - ₱${maxGrandTotal.toLocaleString('en-PH')}`;
}