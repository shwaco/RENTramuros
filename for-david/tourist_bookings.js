// hardcoded data for testing purposes only
const hardcodedBookings = [
    {
        booking_request_id: 54,
        status: 'Completed',
        booking_date: '2026-04-28',
        booking_time: '05:16:00',
        adults_and_seniors: 6,
        children: 2,
        infants: 0,
        vehicle_type: 'Kalesa',
        vehicle_price: 1500.00,
        number_of_vehicle: 3,
        first_name: '',
        last_name: '',
        email_address: '',
        phone_number: '',
        package_name: 'Walled City Grand Tour',
        package_price: 100.00,
        destinations: 'Casa Manila|800,Fort Santiago|800,Minor Basilica|0,San Agustin Museum|1600'
    }
];

function loadTouristHistory() {
    const container = document.getElementById('touristHistoryContainer');

    container.innerHTML = hardcodedBookings.map((tour, index) => {
        const dateObj = new Date(tour.booking_date);
        const formattedDate = dateObj.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: '2-digit' });
        
        const timeString = "5AM"; 
        const tourTitle = tour.package_name ? tour.package_name : 'Custom Attractions Tour';
        
        let statusText = tour.status; 
        let statusClass = '';
        
        if (tour.status === 'Completed' || tour.status === 'Done') {
            statusText = 'Completed';
            statusClass = 'status-completed';
        }

        return `
            <div class="booking-card" onclick="viewTouristReceipt(${index})">
                <div class="bc-left"><span class="bc-id">${tour.booking_request_id}</span></div>
                <div class="bc-middle">
                    <span class="bc-date">${formattedDate} ${timeString}</span>
                    <span class="bc-title">${tourTitle}</span>
                </div>
                <div class="bc-right"><span class="bc-status ${statusClass}">${statusText}</span></div>
            </div>
        `;
    }).join('');
}

document.addEventListener('DOMContentLoaded', loadTouristHistory);

function buildDestinationsHTML(destinationsString, adults = 0, children = 0, isPackage = false, fallback = 'No Custom Attractions Selected') {
    const raw = destinationsString || fallback;
    const pax = (parseInt(adults) || 0) + (parseInt(children) || 0);
    const multiplier = pax > 0 ? pax : 1;

    return raw.split(',').map(dest => {
        const trimmed = dest.trim();
        if (trimmed === fallback || trimmed === 'No Custom Attractions Selected' || trimmed === '') {
            return `<span>${fallback}</span>`;
        }
        
        const parts = trimmed.split('|');
        const name = parts[0] ? parts[0].trim() : '';
        const baseFee = parts[1] ? parseFloat(parts[1]) : 0;
        
        // DITO KO BINAGO YUNG PRICE BASE SA SCREENSHOT MO (Direct fee passing without multiplier bug)
        const totalFee = baseFee;

        if (totalFee > 0) {
            return `<span>${name}&nbsp;&nbsp;<span style="color: #109620; font-weight: 600; font-style: italic; font-size: 0.8rem;">₱${totalFee}</span></span>`;
        }
        return `<span>${name}</span>`;
    }).join('');
}

function calculateTotalFee(destinationsString, packagePrice, adults, children, vehiclePrice, isPackage, numberOfVehicles) {
    let vPrice = parseFloat(vehiclePrice) || 0;
    let pPrice = parseFloat(packagePrice) || 0;
    
    let pax = (parseInt(adults) || 0) + (parseInt(children) || 0);
    const multiplier = pax > 0 ? pax : 1;

    let vehicles = parseInt(numberOfVehicles) || 0;
    const vMultiplier = vehicles > 0 ? vehicles : 1;
    
    let baseTotal = (vPrice * vMultiplier);

    if (isPackage) {
        baseTotal += (pPrice * multiplier);
    } else {
       if (destinationsString && destinationsString.trim() !== '') {
            destinationsString.split(',').forEach(dest => {
                const parts = dest.trim().split('|');
                const fee = parts[1] ? parseFloat(parts[1]) : 0;
                if (fee > 0) baseTotal += fee; 
            });
        }
    }

    if (baseTotal > 0) {
        // estimated range (20%)
        const maxTotal = baseTotal * 1.20;
        const formattedMin = baseTotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const formattedMax = maxTotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        return `₱${formattedMin} - ₱${formattedMax}`;
    }

    return '₱0.00';
}

function buildReceiptHTML({ id, formattedDate, adults_and_seniors, children, infants, package_name, package_price_val = 0, vehicle_price_val = 0, destinations, destinationsHTML, vehicle_type, number_of_vehicle, first_name, last_name, email_address, phone_number, actionArea = '' }) {
    
    const isPackage = package_name && package_name !== 'No Package'; 
    const packagePrice = parseFloat(package_price_val) || 0;
    const vehiclePrice = parseFloat(vehicle_price_val) || 0;
    const vehicleCount = parseInt(number_of_vehicle) || 0;
    
    const feeDisplay = calculateTotalFee(destinations, packagePrice, adults_and_seniors, children, vehiclePrice, isPackage, vehicleCount);
    
    let vehicleDisplayString = `${vehicle_type || 'NONE'}`;
    if (vehiclePrice > 0) {
        const totalVehicleCost = vehiclePrice * (vehicleCount > 0 ? vehicleCount : 1);
        vehicleDisplayString += `&nbsp;&nbsp;<span style="color: #109620; font-weight: 600; font-style: italic; font-size: 0.8rem;">₱${totalVehicleCost}</span>`;
    }

    return `
        <div style="display: flex; justify-content: space-between; align-items: center; margin: 0 -2rem; padding: 1.5rem 2rem 1rem 2rem; border-bottom: 1px solid #e5e7eb;">
            <div style="background-color: #000000; color: #ffffff; font-family: 'Roboto Condensed', sans-serif; font-size: 1.4rem; font-weight: 700; padding: 0.4rem 1.2rem; border-radius: 4px; display: inline-flex; justify-content: center; align-items: center; line-height: 1;">
                ${id}
            </div>
            <button onclick="closeReceipt()" style="background:none; border:none; font-size:2rem; cursor:pointer; color:#9ca3af; font-style: normal; line-height: 1; padding: 0;">&times;</button>
        </div>

        <div style="text-align: right; font-size: 0.8rem; color: #000000; margin-top: 1.5rem; margin-bottom: 2rem; font-family: 'Roboto Condensed', sans-serif; font-weight: 400;">
            ${formattedDate}
        </div>

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; color:#000; font-family: 'Roboto Condensed', sans-serif;">TOURIST</div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem;">
            <span style="padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ADULTS & SENIORS</span>
            <span style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(18 years old and above)</span>
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;">${adults_and_seniors || 0}</span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem;">
            <span style="padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CHILDREN</span>
            <span style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(2 to 17 years old)</span>
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;">${children || 0}</span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:1.5rem; font-size:0.85rem;">
            <span style="padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">INFANTS</span>
            <span style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(under 2 years old)</span>
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;">${infants || 0}</span>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.85rem;">
            <span style="font-weight:700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">PACKAGE</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${package_name || 'No Package'}</span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ITINERARY</div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; font-size: 0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin-bottom: 1.5rem;">
            ${destinationsHTML}
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; font-size: 0.85rem;">
            <span style="font-weight:700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">VEHICLE</span>
            
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000; text-transform: uppercase; text-align: center;">${vehicleDisplayString}</span>
            
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000; text-align: right; font-weight: bold;">${number_of_vehicle || 0}</span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CONTACT INFORMATION</div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">FULL NAME:</span>
            <span style="text-transform: uppercase; font-family: 'Roboto Condensed', sans-serif; color: #000000;">${first_name || ''} ${last_name || ''}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">EMAIL ADDRESS:</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${email_address || ' '}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">PHONE NUMBER:</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${phone_number || ' '}</span>
        </div>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 1.5rem 0;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                <span style="font-weight: 700; font-family: 'Roboto Condensed', sans-serif; color: #000000; font-size: 0.9rem;">TOTAL FEE:</span>
                <span style="font-weight: 600; font-family: 'Roboto Condensed', sans-serif; color: #109620; font-size: 1rem; font-style: italic;">${feeDisplay}</span>
            </div>
            ${actionArea}
        </div>
    `;
}

function closeReceipt() {
    const modalOverlay = document.getElementById('tourist-receipt-overlay');
    if (modalOverlay) modalOverlay.style.display = 'none';
}

function viewTouristReceipt(index) {
    const booking = hardcodedBookings[index];
    if (!booking) return;

    const formattedDate = 'April 28, 2026 ; 05:16 AM';
    const isPackage = booking.package_name ? true : false;
    const destinationsHTML = buildDestinationsHTML(booking.destinations, booking.adults_and_seniors, booking.children, isPackage, 'No destinations listed');

    const modalBody = document.getElementById('tourist-receipt-content');
    modalBody.innerHTML = buildReceiptHTML({
        id: booking.booking_request_id, formattedDate, adults_and_seniors: booking.adults_and_seniors,
        children: booking.children, infants: booking.infants, package_name: booking.package_name,
        package_price_val: booking.package_price, vehicle_price_val: booking.vehicle_price, 
        destinations: booking.destinations, destinationsHTML, vehicle_type: booking.vehicle_type,
        number_of_vehicle: booking.number_of_vehicle, first_name: booking.first_name,
        last_name: booking.last_name, email_address: booking.email_address, phone_number: booking.phone_number,
        actionArea: '' 
    });

    document.getElementById('tourist-receipt-overlay').style.display = 'flex';
}