// FOR TESTINGGG LANG pwede mo imodify to since di pa naman to connected sa db
const hardcodedBookings = [
    {
        // Accepted Status = Lalabas ang CANCEL at DONE
        booking_request_id: 54,
        status: 'Accepted', 
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
    },
    {
        // Pending Status = Lalabas ang CANCEL lang
        booking_request_id: 55,
        status: 'Pending', 
        booking_date: '2026-04-29',
        booking_time: '09:00:00',
        adults_and_seniors: 2,
        children: 0,
        infants: 0,
        vehicle_type: 'Tranvia',
        vehicle_price: 2500.00,
        number_of_vehicle: 1,
        package_name: 'Hero\'s Trail',
        package_price: 150.00,
        destinations: 'Fort Santiago|200,Rizal Shrine|0'
    },
    {
        // Completed Status = Walang buttons na lalabas
        booking_request_id: 56,
        status: 'Completed', 
        booking_date: '2026-04-25',
        booking_time: '14:30:00',
        adults_and_seniors: 4,
        children: 0,
        infants: 0,
        vehicle_type: 'TukTuk',
        vehicle_price: 1000.00,
        number_of_vehicle: 2,
        package_name: null,
        package_price: null,
        destinations: 'Baluarte de San Diego|0'
    }
];

function loadTouristHistory() {
    const container = document.getElementById('touristHistoryContainer');

    container.innerHTML = hardcodedBookings.map((tour, index) => {
        const dateObj = new Date(tour.booking_date);
        const formattedDate = dateObj.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: '2-digit' });
        
        // Mock time string formatting based on raw time
        const timeParts = tour.booking_time.split(':');
        const hour = parseInt(timeParts[0]);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const formattedHour = hour % 12 || 12;
        const timeString = `${formattedHour}:${timeParts[1]} ${ampm}`;
        
        const tourTitle = tour.package_name ? tour.package_name : 'Custom Attractions Tour';
        
        let statusText = tour.status; 
        let statusClass = `status-${tour.status.toLowerCase()}`;
        if (tour.status === 'Done') { statusText = 'Completed'; statusClass = 'status-completed'; }

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

    return raw.split(',').map(dest => {
        const trimmed = dest.trim();
        if (trimmed === fallback || trimmed === '') return `<span style="font-family: 'Roboto Condensed', sans-serif; font-size: 0.85rem;">${fallback}</span>`;
        
        const parts = trimmed.split('|');
        const name = parts[0] ? parts[0].trim() : '';
        const fee = parts[1] ? parseFloat(parts[1]) : 0;
        
        if (fee > 0) {
            return `<span style="font-family: 'Roboto Condensed', sans-serif; font-size: 0.85rem;">${name}&nbsp;&nbsp;<span style=\"color: #109620; font-weight: 700; font-style: italic; font-size: 0.9rem;\">₱${fee}</span></span>`;
        }
        return `<span style="font-family: 'Roboto Condensed', sans-serif; font-size: 0.85rem;">${name}</span>`;
    }).join('');
}

function calculateTotalFee(destinationsString, packagePrice, adults, children, vehiclePrice, isPackage, numberOfVehicles) {
    let vPrice = parseFloat(vehiclePrice) || 0;
    let pPrice = parseFloat(packagePrice) || 0;
    let pax = (parseInt(adults) || 0) + (parseInt(children) || 0);
    const multiplier = pax > 0 ? pax : 1;
    let vehicles = parseInt(numberOfVehicles) || 0;
    const vMultiplier = vehicles > 0 ? vehicles : 1;
    
    // Base Total = Vehicle 
    let baseTotal = (vPrice * vMultiplier); // Ex: 1500 * 3 = 4500

    // Add Package Price if applicable
    if (isPackage) {
        baseTotal += (pPrice * multiplier); // Ex: 100 * 8 = 800
    } 
    
    // FIX: Palaging isama ang Destinations Fee para magmatch sa screenshot!
    if (destinationsString && destinationsString.trim() !== '') {
        destinationsString.split(',').forEach(dest => {
            const parts = dest.trim().split('|');
            const fee = parts[1] ? parseFloat(parts[1]) : 0;
            if (fee > 0) baseTotal += fee; // Ex: 800 + 800 + 1600 = 3200
        });
    }

    // Apply Guide Fee Range (1k - 1.5k) to the Final Base
    if (baseTotal > 0) {
        const minTotal = baseTotal + 1000; 
        const maxTotal = baseTotal + 1500; 
        const formattedMin = minTotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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
        vehicleDisplayString += `&nbsp;&nbsp;<span style="color: #109620; font-weight: 700; font-style: italic; font-size: 0.85rem;">₱${totalVehicleCost}</span>`;
    }

    return `
        <div style="display: flex; justify-content: space-between; align-items: center; margin: 0 -2rem; padding: 0 2rem 1.5rem 2rem; border-bottom: 1px solid #e5e7eb;">
            <div style="background-color: #000000; color: #ffffff; font-family: 'Roboto Condensed', sans-serif; font-size: 1.3rem; font-weight: 700; padding: 0.4rem 1rem; border-radius: 4px; display: inline-flex; justify-content: center; align-items: center; line-height: 1;">
                ${id}
            </div>
            <button onclick="closeReceipt()" style="background:none; border:none; font-size:1.8rem; cursor:pointer; color:#9ca3af; font-style: normal; line-height: 1; padding: 0;">&times;</button>
        </div>

        <div style="text-align: right; font-size: 0.75rem; color: #000000; margin-top: 1.5rem; margin-bottom: 2rem; font-family: 'Roboto Condensed', sans-serif; font-weight: 500;">
            ${formattedDate}
        </div>

        <div style="font-weight:700; font-size:0.85rem; margin-bottom:1rem; color:#000; font-family: 'Roboto Condensed', sans-serif;">TOURIST</div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">
            <span style="font-weight: 500;">ADULTS & SENIORS</span>
            <span style="font-weight: 300; font-style:italic; text-align: center; color: #000000;">(18 years old and above)</span>
            <span style="text-align: right; font-weight: 600;">${adults_and_seniors || 0}</span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">
            <span style="font-weight: 500;">CHILDREN</span>
            <span style="font-weight: 300; font-style:italic; text-align: center; color: #000000;">(2 to 17 years old)</span>
            <span style="text-align: right; font-weight: 600;">${children || 0}</span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:1.5rem; font-size:0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">
            <span style="font-weight: 500;">INFANTS</span>
            <span style="font-weight: 300; font-style:italic; text-align: center; color: #000000;">(under 2 years old)</span>
            <span style="text-align: right; font-weight: 600;">${infants || 0}</span>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">
            <span style="font-weight:700;">PACKAGE</span>
            <span style="font-weight: 500;">${package_name || 'No Package'}</span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight:700; font-size:0.85rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ITINERARY</div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 2rem;">
            ${destinationsHTML}
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; font-size: 0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">
            <span style="font-weight:700;">VEHICLE</span>
            <span style="text-transform: uppercase; text-align: center; font-weight: 500;">${vehicleDisplayString}</span>
            <span style="text-align: right; font-weight: 700;">${number_of_vehicle || 0}</span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight:700; font-size:0.85rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CONTACT INFORMATION</div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">
            <span style="font-weight: 500;">FULL NAME:</span>
            <span style="text-transform: uppercase; font-weight: 500;">${first_name || ''} ${last_name || ''}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">
            <span style="font-weight: 500;">EMAIL ADDRESS:</span>
            <span style="font-weight: 500;">${email_address || ' '}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">
            <span style="font-weight: 500;">PHONE NUMBER:</span>
            <span style="font-weight: 500;">${phone_number || ' '}</span>
        </div>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 1.5rem 0;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <span style="font-weight: 700; font-family: 'Roboto Condensed', sans-serif; color: #000000; font-size: 0.9rem;">TOTAL FEE:</span>
                <span style="font-weight: 700; font-family: 'Roboto Condensed', sans-serif; color: #109620; font-size: 0.95rem; font-style: italic;">${feeDisplay}</span>
            </div>
            ${actionArea}
        </div>
    `;
}

function viewTouristReceipt(index) {
    const booking = hardcodedBookings[index];
    if (!booking) return;

    // Formatting Date specifically for the receipt
    const dateObj = new Date(booking.booking_date || Date.now());
    const formattedDatePart = dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    
    // Formatting Time specifically for the receipt
    const timeParts = booking.booking_time.split(':');
    const hour = parseInt(timeParts[0]);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const formattedHour = hour % 12 || 12;
    const formattedTimePart = `${formattedHour.toString().padStart(2, '0')}:${timeParts[1]} ${ampm}`;
    
    const formattedDate = `${formattedDatePart} ; ${formattedTimePart}`;

    const isPackage = booking.package_name ? true : false;
    const destinationsHTML = buildDestinationsHTML(booking.destinations, booking.adults_and_seniors, booking.children, isPackage, 'No destinations listed');

    // FLOW: Pagseset ng tamang buttons depende sa status
   let actionArea = '';
    
    if (booking.status === 'Pending') {
        // Pending = Cancel button lang (Sagad sa kanan)
        actionArea = `
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-left: auto;">
                <button onclick="touristAction('Cancel')" style="background-color: #FF0000; color: #ffffff; border: none; padding: 0.6rem 2rem; font-size: 1rem; font-weight: 900; border-radius: 4px; cursor: pointer; font-family: 'Roboto Condensed', sans-serif;">CANCEL</button>
            </div>
        `;
    } else if (booking.status === 'Accepted') {
        // Accepted (On Tour) = Cancel at Done buttons (Sagad sa kanan)
        actionArea = `
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-left: auto;">
                <button onclick="touristAction('Cancel')" style="background-color: #FF0000; color: #ffffff; border: none; padding: 0.6rem 2rem; font-size: 1rem; font-weight: 900; border-radius: 4px; cursor: pointer; font-family: 'Roboto Condensed', sans-serif;">CANCEL</button>
                <button onclick="touristAction('Mark as Done')" style="background-color: #109620; color: #ffffff; border: none; padding: 0.6rem 2rem; font-size: 1rem; font-weight: 900; border-radius: 4px; cursor: pointer; font-family: 'Roboto Condensed', sans-serif;">DONE</button>
            </div>
        `;
    }

    const modalBody = document.getElementById('tourist-receipt-content');
    modalBody.innerHTML = buildReceiptHTML({
        id: booking.booking_request_id, formattedDate, adults_and_seniors: booking.adults_and_seniors,
        children: booking.children, infants: booking.infants, package_name: booking.package_name,
        package_price_val: booking.package_price, vehicle_price_val: booking.vehicle_price, 
        destinations: booking.destinations, destinationsHTML, vehicle_type: booking.vehicle_type,
        number_of_vehicle: booking.number_of_vehicle, first_name: booking.first_name,
        last_name: booking.last_name, email_address: booking.email_address, phone_number: booking.phone_number,
        actionArea: actionArea 
    });

    document.getElementById('tourist-receipt-overlay').style.display = 'flex';
}

function closeReceipt() {
    document.getElementById('tourist-receipt-overlay').style.display = 'none';
}

function touristAction(actionName) {
    alert("Ito ang test function para sa: " + actionName);
}