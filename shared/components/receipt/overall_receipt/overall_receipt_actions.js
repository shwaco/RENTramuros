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
           <div class="rcpt-action-area">
               <button onclick="acceptTour(${tourist.booking_request_id})" class="accept-tour-btn">
                    ACCEPT
                </button>
           </div>
        `;
    }

    // Ipapasa sa buildReceiptHTML yung kumpletong data para mag-generate ng resibo
    openReceiptModal(buildReceiptHTML({
        id: tourist.unique_id,
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
    console.log("Accepting tour for ID:", touristId); // Debug check
    
    openDynamicModal(
        "Accept tour?",
        "You are accepting this tour. Cancellation or abandonment can lead to legal action.",
        () => executeAcceptTour(touristId),
        "#109620"
    );
}

// nagsesend ng POST request sa accept bookings API kasama yung tourist ID
// kapag successful, nire-reload ang page para lumipat sa On Tour view ang guide
async function executeAcceptTour(touristId) {
    try {
        const response = await fetch('../../backend/api/actions/receipt/post_accept_bookings.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ tourist_id: touristId })
        });
        const result = await response.json();

        if (result.success) {
            location.reload();
        } else {
            alert("Database Error: " + (result.error || "Could not accept the tour."));
        }
    } catch (error) {
        console.error("Network Error:", error);
        alert("Something went wrong communicating with the server.");
    }
}