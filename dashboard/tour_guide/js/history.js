// kinocall niya yung get_history.php API (via GET request) 
// tapos isesend niya sa DOM para maging history cards

 async function loadHistory() {
    try {
        const response = await fetch('../../backend/api/ui/tourguide/get_guide_history.php')
        const data = await response.json();
        const container = document.getElementById('historyContainer');

        if (data.success && data.history.length > 0) {
            historyTours = data.history;

            // Iloloop niya isa-isa yung result galing database
            container.innerHTML = historyTours.map((tour, index) => {
                // Format Date to match image: "04/29/26 8AM"
                const rawDate = tour.booking_date
                    ? (tour.booking_time ? `${tour.booking_date} ${tour.booking_time}` : tour.booking_date)
                    : null;
                const dateObj = rawDate ? new Date(rawDate.replace(/-/g, '/')) : new Date();
                const formattedDate = dateObj.toLocaleDateString('en-US', { 
                    month: '2-digit', 
                    day: '2-digit', 
                    year: '2-digit' 
                });
                // Extract just the hour and AM/PM (e.g., "8 AM") and remove the space
                const timeString = dateObj.toLocaleTimeString('en-US', { 
                    hour: 'numeric', 
                    hour12: true 
                }).replace(' ', '');

                // Fallback title if it's an Attractions tour instead of a Package
                const tourTitle = tour.package_name ? tour.package_name : 'Cultural Tour';
                let statusText = 'Completed';
                let statusClass = 'status-completed';

                // here din nakabased yung design sa css (status)
                if (tour.status === 'Cancelled') {
                    statusText = 'Cancelled';
                    statusClass = 'status-cancelled';
                } else if (tour.status === 'Accepted') {
                    statusText = 'Accepted';
                    statusClass = 'status-accepted';
                } else if (tour.status !== 'Done') {
                    statusText = tour.status;
                    statusClass = '';
                }

                // para to sa container/card ng bawat tour sa history view, ginagawa niyang clickable yung buong card para makita yung receipt modal
                return `
                    <div class="booking-card" onclick="viewHistoryReceipt(${index})">
                        <div class="bc-left">
                            <span class="bc-id">${tour.unique_id}</span>
                        </div>
                        <div class="bc-middle">
                            <span class="bc-date">${formattedDate} ${timeString}</span>
                            <span class="bc-title">${tourTitle}</span>
                        </div>
                        <div class="bc-right">
                            <span class="bc-status ${statusClass}">${statusText}</span>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            container.innerHTML = '<div style="padding: 3rem; text-align: center; color: #9ca3af; font-style: italic;">No history available yet.</div>';
        }
    } catch (e) {
        console.error("Failed to load history:", e);
    }
}

// Keep your existing viewHistoryReceipt(index) function below this exactly as it is!

// kinukuha yung tour data mula sa historyTours array gamit index 
// ginegenerate yung receipt HTML at inoopen yung modal para sa history view
function viewHistoryReceipt(index) {
    const tour = historyTours[index];
    if (!tour) return console.error("History tour not found!");

    // formatter ng date para sa receipt display
    const rawDate = tour.booking_date
        ? (tour.booking_time ? `${tour.booking_date} ${tour.booking_time}` : tour.booking_date)
        : null;
    const dateObj = rawDate ? new Date(rawDate.replace(/-/g, '/')) : new Date();
    const formattedDate =
        dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) +
        ' ; ' +
        dateObj.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit' });

    const isPackage = tour.package_name ? true : false;
    const destinationsHTML = buildDestinationsHTML(tour.destinations, tour.adults_and_seniors, tour.children, isPackage, 'No destinations listed');

    // ginegenerate yung receipt HTML and inoopen yung modal para sa history view
    openReceiptModal(buildReceiptHTML({
        id: tour.unique_id,
        formattedDate,
        adults_and_seniors: tour.adults_and_seniors,
        children: tour.children,
        infants: tour.infants,
        package_name: tour.package_name,
        package_price_val: tour.package_price,
        vehicle_price_val: tour.vehicle_price,
        destinations: tour.destinations,
        destinationsHTML,
        vehicle_type: tour.vehicle_type,
        number_of_vehicle: tour.number_of_vehicle,
        first_name: tour.first_name,
        last_name: tour.last_name,
        email_address: tour.email_address,
        phone_number: tour.phone_number,
        actionArea: ''
    }));
}