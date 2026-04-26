// para to sa confirmations
function openDynamicModal(title, message, confirmCallback, buttonColor = '#16a34a') {
    document.getElementById('dynamic-modal-title').innerText = title;
    document.getElementById('dynamic-modal-msg').innerText = message;
    
    const confirmBtn = document.getElementById('dynamic-modal-btn');
    confirmBtn.style.backgroundColor = buttonColor;
    
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    
    newConfirmBtn.onclick = function() {
        closeDynamicModal();
        confirmCallback(); 
    };
    
    document.getElementById('dynamic-confirm-overlay').style.display = 'flex';
}

function closeDynamicModal() {
    document.getElementById('dynamic-confirm-overlay').style.display = 'none';
}

// global variable para sa attraction fees na hinahanap from API
let attractionFeesDB = {}; 

// Function to fetch data mula sa mga API hereee
async function loadAttractionsData() {
    try {
        // tiga-fetch from exact API endpoint
        const response = await fetch('../api/retrieve_attractions.php');
        const result = await response.json();
        
        if (result.status === 'success') {
            // tiga convert ng array into lookup dictionary: { "Fort Santiago": 75, "Casa Manila": 75 }
            result.data.forEach(item => {
                const name = item.attraction_name.trim();
                const fee = parseFloat(item.entrance_fee);
                attractionFeesDB[name] = fee;
            });
        }
    } catch (error) {
        console.error("Failed to load attractions from API:", error);
    }
}

// global state ng guide dashboard at queue lobby
let waitingTourists = [];
let historyTours = [];
let claimTimerInterval = null;
let currentQueuePosition = typeof queuePosition !== 'undefined' ? queuePosition : null;


// accept button functions
function acceptTour(customerId) {
    if (!customerId) return alert("Error: Could not find the Tourist ID.");
    openDynamicModal(
        "Accept tour?", 
        "You are accepting this tour. Cancellation or any form of abandonment can lead to legal action.", 
        () => executeAcceptTour(customerId), 
        "#16a34a" // Green
    );
}

// tiga send ng request sa backend para i-accept yung tour ng guide
async function executeAcceptTour(customerId) {
    try {
        const response = await fetch('api/accept_tour.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ customer_id: customerId })
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

// tiga ng request para mag-join sa queue (Available status)
async function joinQueue() {
    try {
        const res = await fetch('api/join_queue.php', { method: 'POST' });
        const data = await res.json();
        if (data.success) location.reload();
    } catch (e) {
        console.error("Error joining queue", e);
    }
}

// clock in shts
async function clockIn() {
    try {
        const res = await fetch('api/clock_in.php', { method: 'POST' });
        const data = await res.json();
        if (data.success) location.reload();
    } catch (e) {
        console.error("Error clocking in", e);
    }
}

// regular polling para i-check sa server kung may updates sa queue position
function startPolling() {
    setInterval(async () => {
        // kung On Tour yung guide, walang reason mag-poll para sa position
        if (typeof CURRENT_GUIDE_STATUS !== 'undefined' && CURRENT_GUIDE_STATUS === 'On Tour') return; 

        try {
            const response = await fetch('api/check_queue.php');
            const data = await response.json();
            
            if (data.success && data.status === 'Queuing') {
                // kung nag-change ang position sa queue, mag-reload ng page
                if (currentQueuePosition !== null && currentQueuePosition !== data.position) {
                    window.location.reload();
                }
                currentQueuePosition = data.position;
            }
        } catch (e) {
            console.error("Radar error:", e);
        }
    }, 5000);
}

// kinukuha yung mga waiting tourists sa server and inaaupdt inaupdate yung lobby display
async function initWaitingLobby() {
    const lobbyContainer = document.getElementById('tourist-lobby');
    const idleStateText = document.getElementById('idle-state-text');

    if (!lobbyContainer) return;

    try {
        const response = await fetch('api/get_waiting_tourists.php');
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            waitingTourists = result.data; 

            if (idleStateText) idleStateText.style.display = 'none';

            lobbyContainer.innerHTML = waitingTourists.map(tourist => {
                const displayTime = tourist.created_at || tourist.called_at;
                let timeString = "00:00";
                let dateString = "00/00/00";
                
                if (displayTime) {
                    let safeDateStr = displayTime.replace(/-/g, '/');
                    const dateObj = new Date(safeDateStr);
                    
                    if (!isNaN(dateObj.getTime())) {
                        timeString = dateObj.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                        dateString = dateObj.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: '2-digit' });
                    }
                }
                return `
                <div class="t-block" onclick="viewTouristDetails(${tourist.customer_id})">
                    <div class="t-block-left">${tourist.customer_id}</div>
                    <div class="t-block-right">
                        <span style="font-weight:bold; color:#000;">${dateString}</span>
                        <span>${timeString}</span>
                    </div>
                </div>
                `;
            }).join('');
            
            // if number 1 sa queue, start na yung claim timer
            if (typeof IS_QUEUE_NUMBER_ONE !== 'undefined' && IS_QUEUE_NUMBER_ONE) {
                startClaimTimer();
            }
        } else {
            if (idleStateText) idleStateText.style.display = 'none';
            
            lobbyContainer.innerHTML = `
                <div style="width: 100%; text-align: center; padding: 1rem 0; color: #000000; font-family: 'Roboto Condensed', sans-serif; font-size: 1.1rem; font-style: italic; font-weight: 300;">
                    No tours yet
                </div>
            `;
        }
    } catch (error) {
        console.error("Error loading lobby:", error);
    }
}

// 15-second timer para sa guide na nangunguna sa queue para mag-accept ng tourist
function startClaimTimer() {
    let timeLeft = 15;
    const timerDisplay = document.getElementById('selection-timer');
    
    if (claimTimerInterval) clearInterval(claimTimerInterval);

    claimTimerInterval = setInterval(async () => {
        timeLeft--;
        if (timerDisplay) timerDisplay.innerText = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(claimTimerInterval);
            
            try {
                // tiga call ng missed turn API para i-move ang guide sa back ng queue
                await fetch('api/missed_turn.php', { method: 'POST' });
            } catch(e) {
                console.error("Could not process missed turn", e);
            }
            
            alert("Time is up! You have been moved to the back of the queue.");
            window.location.reload();
        }
    }, 1000);
}

// tiga kuha ng completed tour history ng guide
async function loadHistory() {
    try {
        const response = await fetch('api/get_history.php');
        const data = await response.json();
        const tableBody = document.getElementById('historyTable');

        if (data.success && data.history.length > 0) {
            historyTours = data.history; 

            tableBody.innerHTML = historyTours.map((tour, index) => {
                const dateObj = new Date(tour.completed_at);
                const formattedDate = dateObj.toLocaleDateString('en-US', { 
                    month: '2-digit', day: '2-digit', year: 'numeric' 
                }) + ', ' + dateObj.toLocaleTimeString('en-US', { 
                    hour: '2-digit', minute: '2-digit', hour12: true 
                });

                return `
                    <tr class="history-row" onclick="viewHistoryReceipt(${index})" style="cursor: pointer;" title="Click to view full receipt">
                        <td style="font-weight: 500;">${tour.first_name} ${tour.last_name}</td>
                        <td style="color: #4b5563;">${formattedDate}</td>
                        <td style="font-weight: 500; color: #111827;">&#10003;Completed</td>
                    </tr>
                `;
            }).join('');
        } else {
            tableBody.innerHTML = '<tr><td colspan="3" style="padding: 3rem; text-align: center; color: #9ca3af; font-style: italic;">No history available yet.</td></tr>';
        }
    } catch (e) {
        console.error("Failed to load history:", e);
    }
}

function viewHistoryReceipt(index) {
    const tour = historyTours[index];
    if (!tour) return console.error("History tour not found!");

    const modalBody = document.getElementById('tourist-receipt-content');
    if (!modalBody) return;

    const dateObj = new Date(tour.completed_at || Date.now());
    const formattedDate = dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) + ' ; ' + 
                          dateObj.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit' });

    const destinationsString = tour.destinations || 'No itineraries listed';
    const destinationsHTML = destinationsString.split(',').map(dest => {
        if (dest.trim() === 'No destinations listed' || dest.trim() === 'No itineraries listed') {
            return `<span>${dest.trim()}</span>`;
        }
        
        // tiga-split ng string sa Name at Fee gamit ang '|' delimiter (kung meron man)
        const parts = dest.split('|');
        const name = parts[0] ? parts[0].trim() : '';
        const fee = parts.length > 1 && parts[1] ? parseInt(parts[1], 10) : 0;
        
        if (fee > 0) {
            return `<span>${name}&nbsp;&nbsp;<span style="color: #16a34a; font-weight: 600; font-style: italic; font-size: 0.8rem;">₱${fee}</span></span>`;
        } else {
            return `<span>${name}</span>`;
        }
    }).join('');

    modalBody.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin: 0 -2rem; padding: 1.5rem 2rem 1rem 2rem; border-bottom: 1px solid #e5e7eb;">
            <div style="background-color: #000000; color: #ffffff; font-family: 'Roboto Condensed', sans-serif; font-size: 1.4rem; font-weight: 700; padding: 0.4rem 1.2rem; border-radius: 4px; display: inline-flex; justify-content: center; align-items: center; line-height: 1;">
                ${tour.customer_id}
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
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tour.adult_count || 0}</span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem;">
            <span style="padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CHILDREN</span>
            <span style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(2 to 17 years old)</span>
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tour.children_count || 0}</span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:1.5rem; font-size:0.85rem;">
            <span style="padding-left: 0.5rem;font-family: 'Roboto Condensed', sans-serif; color: #000000;">INFANTS</span>
            <span style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(under 2 years old)</span>
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tour.infant_count || 0}</span>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.85rem;">
            <span style="font-weight:700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">PACKAGE</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tour.package_name || 'Sacred Route'}</span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ITINERARY</div>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; font-size: 0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin-bottom: 1.5rem;">
            ${destinationsHTML}
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; font-size: 0.85rem;">
            <span style="font-weight:700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">VEHICLE</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000; text-transform: uppercase; text-align: center;">${tour.vehicle_type || 'NONE'}</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000; text-align: right; font-weight: bold;">${tour.vehicle_count || 0}</span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CONTACT INFORMATION</div>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">FULL NAME:</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tour.first_name || ''} ${tour.last_name || ''}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">EMAIL ADDRESS:</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tour.email || 'N/A'}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">PHONE NUMBER:</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tour.phone_number || 'N/A'}</span>
        </div>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 1.5rem 0;">

        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <span style="font-weight:700; font-family: 'Roboto Condensed', sans-serif; color: #000000; font-size: 0.9rem;">TOTAL FEE:</span>
            <span style="font-weight: 700; font-family: 'Roboto Condensed', sans-serif; color: #10b981; font-size: 1.3rem;">₱4,500-5000</span>
        </div>
    `;

    const modalContainer = document.getElementById('tourist-receipt-overlay');
    if (modalContainer) modalContainer.style.display = 'flex';
}

function viewTouristDetails(id) {
    const tourist = waitingTourists.find(t => t.customer_id == id);
    if (!tourist) return console.error("Tourist not found in array!");

    const modalBody = document.getElementById('tourist-receipt-content');
    if (!modalBody) return;

    const dateObj = new Date(tourist.created_at || Date.now());
    const formattedDate = dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) + ' ; ' + 
                          dateObj.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit' });

    const destinationsString = tourist.destinations || 'No destinations listed';
    const destinationsHTML = destinationsString.split(',').map(dest => {
    if (dest.trim() === 'No destinations listed' || dest.trim() === 'No itineraries listed') {
        return `<span>${dest.trim()}</span>`;
    }
    
    // Split the string into Name and Fee using the '|' delimiter
    const parts = dest.split('|');
    const name = parts[0] ? parts[0].trim() : '';
    const fee = parts[1] ? parseInt(parts[1], 10) : 0;
    
    if (fee > 0) {
        return `<span>${name}&nbsp;&nbsp;<span style="color: #16a34a; font-weight: 400; font-style: italic; font-size: 0.8rem;">₱${fee}</span></span>`;
    } else {
        return `<span>${name}</span>`;
    }
}).join('');

    let actionArea = '';
    // if #1 ka sa queue, pwede ka na mamili ng tours so papakita na yung accept tour and start yung claim timer
    if (typeof IS_QUEUE_NUMBER_ONE !== 'undefined' && IS_QUEUE_NUMBER_ONE) {
        actionArea = `
        <div style="display: flex; justify-content: flex-end; margin-top: 2rem;">
            <button onclick="acceptTour(${tourist.customer_id})" class="accept-btn" style="background-color: #109620; color: #ffffff; border: none; padding: 0.6rem 2.5rem; font-size: 1.1rem; font-weight: 900; font-family: 'Roboto Condensed', sans-serif; border-radius: 2px; cursor: pointer; transition: background-color 0.2s;">
                ACCEPT
            </button>
        </div>`;
    }

    modalBody.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin: 0 -2rem; padding: 1.5rem 2rem 1rem 2rem; border-bottom: 1px solid #e5e7eb;">
            <div style="background-color: #000000; color: #ffffff; font-family: 'Roboto Condensed', sans-serif; font-size: 1.4rem; font-weight: 700; padding: 0.4rem 1.2rem; border-radius: 4px; display: inline-flex; justify-content: center; align-items: center; line-height: 1;">
                ${tourist.customer_id}
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
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tourist.adult_count || 0}</span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem;">
            <span style="padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CHILDREN</span>
            <span style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(2 to 17 years old)</span>
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tourist.children_count || 0}</span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:1.5rem; font-size:0.85rem;">
            <span style="padding-left: 0.5rem;font-family: 'Roboto Condensed', sans-serif; color: #000000;">INFANTS</span>
            <span style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(under 2 years old)</span>
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tourist.infant_count || 0}</span>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.85rem;">
            <span style="font-weight:700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">PACKAGE</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tourist.package_name || 'Sacred Route'}</span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ITINERARY</div>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; font-size: 0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin-bottom: 1.5rem;">
            ${destinationsHTML}
        </div>

       <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; font-size: 0.85rem;">
            <span style="font-weight:700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">VEHICLE</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000; text-transform: uppercase; text-align: center;">${tourist.service_type || 'NONE'}</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000; text-align: right; font-weight: bold;">${tourist.vehicle_count || 0}</span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CONTACT INFORMATION</div>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">FULL NAME:</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tourist.first_name} ${tourist.last_name}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">EMAIL ADDRESS:</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tourist.email}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">PHONE NUMBER:</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${tourist.phone_number}</span>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000; font-size: 0.9rem;">TOTAL FEE:</span>
                <span style="font-weight: 400; font-family: 'Roboto Condensed', sans-serif; color: #109620; font-size: 0.9rem; font-style: italic;">₱4,500-5000</span>
            </div>
            ${actionArea ? actionArea.replace('<div style="display: flex; justify-content: flex-end; margin-top: 2rem;">', '<div style="display: flex; justify-content: flex-end;">') : ''}
        </div>
    `;

    const modalContainer = document.getElementById('tourist-receipt-overlay');
    if (modalContainer) modalContainer.style.display = 'flex';
}

function closeReceipt() {
    const modalOverlay = document.getElementById('tourist-receipt-overlay');
    if (modalOverlay) modalOverlay.style.display = 'none';
}

// para sa swtiching ng dashboard at history views
function switchView(viewName) {
    const dashboard = document.getElementById('active-tour-view');
    const history = document.getElementById('history-view');

    if (viewName === 'dashboard') {
        if (dashboard) dashboard.style.display = 'flex';
        if (history) history.style.display = 'none';
    } else if (viewName === 'history') {
        if (dashboard) dashboard.style.display = 'none';
        if (history) history.style.display = 'flex';
    }
}

// naglogout ng guide gamit yung backend API
async function handleLogoutOnly() {
    try {
        // tiga-call ang logout API endpoint
        const response = await fetch('api/logout_api.php', { method: 'POST' }); 
        const result = await response.json();
        
        if (result.status === 'success') {
            window.location.href = "../auth/login.html"; // Redirects to new login UI
        }
    } catch (error) {
        console.error("Logout Error:", error);
    }
}

// tiga bukas nung confirmation modal para sa clock out
function handleClockOut() {
    // Triggers your custom red HTML modal instead of the browser alert
    openDynamicModal(
        "Clock Out?", 
        "Are you sure you want to clock out? You will lose your current place in the queue!", 
        () => executeClockOut(), 
        "#dc2626" // Red button
    );
}

async function executeClockOut() {
    try {
        const response = await fetch('api/clock_out.php', { method: 'POST' });
        const result = await response.json();
        
        if (result.success) {
            window.location.reload(); 
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Clock Out Error:", error);
    }
}

// tiga generate ng lahat ng initialization functions kapag nag-load yung page
document.addEventListener('DOMContentLoaded', () => {
    loadAttractionsData();
    loadHistory();
    initWaitingLobby(); 
    startPolling();

    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    // profile dropdown shts
    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation(); 
            profileMenu.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!profileMenu.contains(e.target)) {
                profileMenu.classList.remove('show');
            }
        });
    }
});