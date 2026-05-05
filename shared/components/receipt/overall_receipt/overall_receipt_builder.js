// Taga-buo ng buong receipt HTML na ilalabas sa loob ng Modal.
function buildReceiptHTML({ id, formattedDate, adults_and_seniors, children, infants, package_name, package_price_val = 0, vehicle_price_val = 0, destinations, destinationsHTML, vehicle_type, number_of_vehicle, first_name, last_name, email_address, phone_number, actionArea = '' }) {
    
    const isPackage = package_name && package_name !== 'No Package'; 
    const packagePrice = parseFloat(package_price_val) || 0;
    const vehiclePrice = parseFloat(vehicle_price_val) || 0;
    const vehicleCount = parseInt(number_of_vehicle) || 0;
    
    // tiga calculate ng Total ackage Cost (Package Price x Pax) para idisplay
    const pax = (parseInt(adults_and_seniors) || 0) + (parseInt(children) || 0);
    const multiplier = pax > 0 ? pax : 1;
    const totalPackageCost = packagePrice * multiplier;

    let packageDisplayString = `${package_name || 'No Package'}`;
    if (isPackage && totalPackageCost > 0) {
        packageDisplayString += `&nbsp;&nbsp;<span class="rcpt-green-text">₱${totalPackageCost.toLocaleString('en-PH')}</span>`;
    }

    const feeData = calculateTotalFee(destinations, packagePrice, adults_and_seniors, children, vehiclePrice, isPackage, vehicleCount);
    
    let vehicleDisplayString = `${vehicle_type || 'NONE'}`;
    if (vehiclePrice > 0) {
        const totalVehicleCost = vehiclePrice * (vehicleCount > 0 ? vehicleCount : 1);
        vehicleDisplayString += `&nbsp;&nbsp;<span class="rcpt-green-sm">₱${totalVehicleCost.toLocaleString('en-PH')}</span>`;
    }

    let templateHTML = document.getElementById('receipt-modal-template').innerHTML;

    return templateHTML
        .replace('{{id}}', id)
        .replace('{{formattedDate}}', formattedDate)
        .replace('{{adults_and_seniors}}', adults_and_seniors || 0)
        .replace('{{children}}', children || 0)
        .replace('{{infants}}', infants || 0)
        .replace('{{packageDisplayString}}', packageDisplayString)
        .replace('{{destinationsHTML}}', destinationsHTML)
        .replace('{{vehicleDisplayString}}', vehicleDisplayString)
        .replace('{{number_of_vehicle}}', number_of_vehicle || 0)
        .replace('{{first_name}}', first_name || '')
        .replace('{{last_name}}', last_name || '')
        .replace('{{email_address}}', email_address || ' ')
        .replace('{{phone_number}}', phone_number || ' ')
        .replace('{{baseStr}}', feeData.baseStr)
        .replace('{{minGrandStr}}', feeData.minGrandStr)
        .replace('{{maxGrandStr}}', feeData.maxGrandStr)
        .replace('{{actionArea}}', actionArea);
}