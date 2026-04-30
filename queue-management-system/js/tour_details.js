// handles yung pagpapakita ng tourist details modal and pag-accept ng tour
// Ito yung nagha-handle kapag kinlick ng guide yung isang waiting tourist block sa lobby.
// Hinahanap niya yung data, binubuo yung modal, and pinapakita yung "Accept" button.
function viewTouristDetails(id) {
    const tourist = waitingTourists.find(t => t.booking_request_id == id);
    if (!tourist) return console.error("Tourist not found in array!");

    // taga format lang ng date
    const dateObj = new Date(tourist.booking_date || Date.now());
    const formattedDate =
        dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) +
        ' ; ' +
        dateObj.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit' });

    // Inaalam natin kung package ba to para hindi ma-doble yung bayad sa attractions
    const isPackage = tourist.package_name ? true : false;
    // then papasa natin lahat ng multiplier data (adults, children, isPackage) papunta sa receipt.js
    const destinationsHTML = buildDestinationsHTML(tourist.destinations, tourist.adults_and_seniors, tourist.children, isPackage, 'No destinations listed');

    let actionArea = '';
    // kung #1 na yung guide sa queue, pwede na siyang pumili ng tourist so lalabas na yung accept button
    if (typeof IS_QUEUE_NUMBER_ONE !== 'undefined' && IS_QUEUE_NUMBER_ONE) {
        actionArea = `
            <button onclick="acceptTour(${tourist.booking_request_id})" class="accept-btn" style="background-color: #109620; color: #ffffff; border: none; padding: 0.6rem 2.5rem; font-size: 1.1rem; font-weight: 900; font-family: 'Roboto Condensed', sans-serif; border-radius: 2px; cursor: pointer; transition: background-color 0.2s;">
                ACCEPT
            </button>
        `;
    }

    // Ipapasa sa buildReceiptHTML yung kumpletong data para mag-generate ng resibo
    openReceiptModal(buildReceiptHTML({
        id: tourist.booking_request_id,
        formattedDate,
        adults_and_seniors: tourist.adults_and_seniors,
        children: tourist.children,
        infants: tourist.infants,
        package_name: tourist.package_name,
        package_price_val: tourist.package_price,
        vehicle_price_val: tourist.vehicle_price,
        destinations: tourist.destinations,
        destinationsHTML,
        vehicle_type: tourist.vehicle_type,
        number_of_vehicle: tourist.number_of_vehicle,
        first_name: tourist.first_name,
        last_name: tourist.last_name,
        email_address: tourist.email_address,
        phone_number: tourist.phone_number,
        actionArea
    }));
}

// nagva-validate muna na may valid tourist ID bago buksan ang confirmation modal
function acceptTour(touristId) {
    if (!touristId) return alert("Error: Could not find the Tourist ID.");
    // inoopen yung confirmation modal muna
    openDynamicModal(
        "Accept tour?",
        "You are accepting this tour. Cancellation or any form of abandonment can lead to legal action.",
        () => executeAcceptTour(touristId),
        "#109620"
    );
}

// nagsesend ng POST request sa accept bookings API kasama yung tourist ID
// kapag successful, nire-reload ang page para lumipat sa On Tour view ang guide
async function executeAcceptTour(touristId) {
    try {
        const response = await fetch('api/post_accept_bookings.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ tourist_id: touristId })
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