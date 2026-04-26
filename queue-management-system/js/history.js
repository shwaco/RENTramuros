// naglo-load ng completed tour history ng guide at pinapakita sa history tab

async function loadHistory() {
    try {
        const response = await fetch('api/get_history.php');
        const data = await response.json();
        const tableBody = document.getElementById('historyTable');

        if (data.success && data.history.length > 0) {
            historyTours = data.history;

            // ginagawang table rows yung bawat tour history entry — clickable para makita yung full receipt
            tableBody.innerHTML = historyTours.map((tour, index) => {
                const dateObj = new Date(tour.completed_at);
                const formattedDate =
                    dateObj.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' }) +
                    ', ' +
                    dateObj.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });

                return `
                    <tr class="history-row" onclick="viewHistoryReceipt(${index})" style="cursor: pointer;" title="Click to view full receipt">
                        <td style="font-weight: 500;">${tour.first_name} ${tour.last_name}</td>
                        <td style="color: #4b5563;">${formattedDate}</td>
                        <td style="font-weight: 500; color: #111827;">&#10003;Completed</td>
                    </tr>
                `;
            }).join('');
        } else {
            // kung wala pang history, ipakita lang yung empty state message
            tableBody.innerHTML = '<tr><td colspan="3" style="padding: 3rem; text-align: center; color: #9ca3af; font-style: italic;">No history available yet.</td></tr>';
        }
    } catch (e) {
        console.error("Failed to load history:", e);
    }
}

function viewHistoryReceipt(index) {
    const tour = historyTours[index];
    if (!tour) return console.error("History tour not found!");

    // formatter ng date para sa receipt display
    const dateObj = new Date(tour.completed_at || Date.now());
    const formattedDate =
        dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) +
        ' ; ' +
        dateObj.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit' });

    const destinationsHTML = buildDestinationsHTML(tour.destinations, 'No itineraries listed');

    // ginagawa yung receipt HTML and inoopen yung modal para sa history view
    openReceiptModal(buildReceiptHTML({
        id: tour.customer_id,
        formattedDate,
        adult_count: tour.adult_count,
        children_count: tour.children_count,
        infant_count: tour.infant_count,
        package_name: tour.package_name,
        destinationsHTML,
        service_type: tour.vehicle_type,
        vehicle_count: tour.vehicle_count,
        first_name: tour.first_name,
        last_name: tour.last_name,
        email: tour.email,
        phone_number: tour.phone_number,
        actionArea: '',
        feeDisplay: '₱4,500-5000'
    }));
}