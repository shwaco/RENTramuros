// handles yung pagpapakita ng tourist details modal and pag-accept ng tour
function viewTouristDetails(id) {
    const tourist = waitingTourists.find(t => t.customer_id == id);
    if (!tourist) return console.error("Tourist not found in array!");

    // taga format lang ng date
    const dateObj = new Date(tourist.created_at || Date.now());
    const formattedDate =
        dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) +
        ' ; ' +
        dateObj.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit' });

    const destinationsHTML = buildDestinationsHTML(tourist.destinations, 'No destinations listed');

    let actionArea = '';
    // kung #1 na yung guide sa queue, pwede na siyang pumili ng tourist so lalabas na yung accept button
    if (typeof IS_QUEUE_NUMBER_ONE !== 'undefined' && IS_QUEUE_NUMBER_ONE) {
        actionArea = `
            <button onclick="acceptTour(${tourist.customer_id})" class="accept-btn" style="background-color: #109620; color: #ffffff; border: none; padding: 0.6rem 2.5rem; font-size: 1.1rem; font-weight: 900; font-family: 'Roboto Condensed', sans-serif; border-radius: 2px; cursor: pointer; transition: background-color 0.2s;">
                ACCEPT
            </button>
        `;
    }

    openReceiptModal(buildReceiptHTML({
        id: tourist.customer_id,
        formattedDate,
        adult_count: tourist.adult_count,
        children_count: tourist.children_count,
        infant_count: tourist.infant_count,
        package_name: tourist.package_name,
        destinationsHTML,
        service_type: tourist.service_type,
        vehicle_count: tourist.vehicle_count,
        first_name: tourist.first_name,
        last_name: tourist.last_name,
        email: tourist.email,
        phone_number: tourist.phone_number,
        actionArea,
        feeDisplay: '₱4,500-5000'
    }));
}

function acceptTour(customerId) {
    if (!customerId) return alert("Error: Could not find the Tourist ID.");
    // inoopen yung confirmation modal muna
    openDynamicModal(
        "Accept tour?",
        "You are accepting this tour. Cancellation or any form of abandonment can lead to legal action.",
        () => executeAcceptTour(customerId),
        "#16a34a"
    );
}

async function executeAcceptTour(customerId) {
    try {
        const response = await fetch('api/post_accept_bookings.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ customer_id: customerId })
        });
        const result = await response.json();

        if (result.success) {
            // kung successful, nirereload yung web para lumipat yung guide sa On Tour view
            location.reload();
        } else {
            alert("Database Error: " + (result.error || "Could not accept the tour."));
        }
    } catch (error) {
        console.error("Network Error:", error);
        alert("Something went wrong communicating with the server.");
    }
}