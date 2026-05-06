// Tourist-side equivalent of history.js
// Calls get_tourist_history.php and renders booking cards identical to the tour guide view

let touristHistoryTours = [];

async function loadTouristHistory() {
    try {
        const response = await fetch('../../backend/api/ui/tourist/get_tourist_history.php');
        const data = await response.json();
        const container = document.getElementById('historyContainer');

        if (data.success && data.history.length > 0) {
            touristHistoryTours = data.history;

            container.innerHTML = touristHistoryTours.map((tour, index) => {
                // Format Date — same logic as guide side: "05/20/26 2PM"
                const rawDate = tour.booking_date
                    ? (tour.booking_time ? `${tour.booking_date} ${tour.booking_time}` : tour.booking_date)
                    : null;
                const dateObj = rawDate ? new Date(rawDate.replace(/-/g, '/')) : new Date();
                const formattedDate = dateObj.toLocaleDateString('en-US', {
                    month: '2-digit',
                    day: '2-digit',
                    year: '2-digit'
                });
                const timeString = dateObj.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    hour12: true
                }).replace(' ', '');

                // Fallback title if no package (custom tour)
                const tourTitle = tour.package_name ? tour.package_name : 'Custom Tour';

                // Status — same classes as my_bookings.css
                let statusText = 'Completed';
                let statusClass = 'status-completed';

                if (tour.status === 'Cancelled') {
                    statusText = 'Cancelled';
                    statusClass = 'status-cancelled';
                } else if (tour.status === 'Accepted') {
                    statusText = 'Accepted';
                    statusClass = 'status-accepted';
                } else if (tour.status === 'Pending') {
                    statusText = 'Pending';
                    statusClass = 'status-pending';
                } else if (tour.status !== 'Done') {
                    statusText = tour.status;
                    statusClass = '';
                }

                return `
                    <div class="booking-card" onclick="viewTouristHistoryReceipt(${index})">
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
            container.innerHTML = '<div style="padding: 3rem; text-align: center; color: #9ca3af; font-style: italic;">No bookings yet.</div>';
        }
    } catch (e) {
        console.error("Failed to load tourist history:", e);
        const container = document.getElementById('historyContainer');
        if (container) {
            container.innerHTML = '<div style="padding: 3rem; text-align: center; color: #9ca3af; font-style: italic;">Could not load bookings.</div>';
        }
    }
}

// Opens the receipt modal for a tourist booking — mirrors viewHistoryReceipt() in the guide side
function viewTouristHistoryReceipt(index) {
    const tour = touristHistoryTours[index];
    if (!tour) return console.error("Tourist history tour not found!");

    const rawDate = tour.booking_date
        ? (tour.booking_time ? `${tour.booking_date} ${tour.booking_time}` : tour.booking_date)
        : null;
    const dateObj = rawDate ? new Date(rawDate.replace(/-/g, '/')) : new Date();
    const formattedDate =
        dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) +
        ' ; ' +
        dateObj.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit' });

    const isPackage = tour.package_name ? true : false;
    const destinationsHTML = buildDestinationsHTML(
        tour.destinations,
        tour.adults_and_seniors,
        tour.children,
        isPackage,
        'No destinations listed'
    );

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

// Kick off on page load
document.addEventListener('DOMContentLoaded', loadTouristHistory);