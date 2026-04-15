// Global state ng guide dashboard at queue lobby
let waitingTourists = [];
let historyTours = [];
let claimTimerInterval = null;
let currentQueuePosition = typeof queuePosition !== 'undefined' ? queuePosition : null;

// Mark tour as completed kapag tapos na ang tour ng tourist
async function finishTour(customerId) {
    if (!customerId) return alert("Error: Could not find the Tourist ID.");
    if (!confirm("Are you sure the tour is finished? You will be placed at the back of the queue.")) return; 

    try {
        const response = await fetch('api/complete_tour.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ customer_id: customerId }) 
        });
        const result = await response.json();

        if (result.success) {
            location.reload(); 
        } else {
            alert("Database Error: " + (result.error || "Could not complete the tour."));
        }
    } catch (error) {
        console.error("Network Error:", error);
        alert("Something went wrong communicating with the server.");
    }
}

// kinacancel yung current tour if need ibalik sa queue ang tourist
async function cancelTour(customerId) {
    if (!customerId) return alert("Error: Could not find the Tourist ID.");
    if (!confirm("Are you sure you want to CANCEL this tour? You will be placed at the back of the queue.")) return; 

    try {
        const response = await fetch('api/cancel_tour.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ customer_id: customerId }) 
        });
        const result = await response.json();

        if (result.success) {
            location.reload(); 
        } else {
            alert("Database Error: " + (result.error || "Could not cancel the tour."));
        }
    } catch (error) {
        console.error("Network Error:", error);
        alert("Something went wrong communicating with the server.");
    }
}

// tiga accept ng tour at taga connect sa tour guide
async function acceptTour(customerId) {
    if (!customerId) return alert("Error: Could not find the Tourist ID.");
    if (!confirm("Are you sure you want to ACCEPT this tour?")) return; 

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

async function joinQueue() {
    try {
        const res = await fetch('api/join_queue.php', { method: 'POST' });
        const data = await res.json();
        if (data.success) location.reload();
    } catch (e) {
        console.error("Error joining queue", e);
    }
}

// regular na nagche-check sa server kung may update sa queue position
function startPolling() {
    setInterval(async () => {
        // tinitigil yung polling kapag busy ang guide para hindi mag-overwrite while on tour
        if (typeof CURRENT_GUIDE_STATUS !== 'undefined' && CURRENT_GUIDE_STATUS === 'Busy') return; 

        try {
            const response = await fetch('api/check_queue.php');
            const data = await response.json();
            
            if (data.success && data.status === 'Available') {
                // if nagbago ang queue position, nirerefresh nito para makita agad ang bagong status
                if (currentQueuePosition !== null && currentQueuePosition !== data.position) {
                    window.location.reload();
                }
                currentQueuePosition = data.position;
            }
        } catch (e) {
            console.error("Radar error:", e);
        }
    }, 4000);
}

// kinukuha yung mga waiting tourists sa server at inaupdate yung lobby display
async function initWaitingLobby() {
    const lobbyContainer = document.getElementById('tourist-lobby');
    const idleStateText = document.getElementById('idle-state-text');

    if (!lobbyContainer) return;

    try {
        const response = await fetch('api/get_waiting_tourists.php');
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            waitingTourists = result.data; 

            // tinatago yung text na "No tours yet" kapag may mga tourists sa lobby para hindi magdoble yung message
            if (idleStateText) idleStateText.style.display = 'none';

            lobbyContainer.innerHTML = waitingTourists.map(tourist => {
                // ginagamit ang created_at timestamp para sa mga tourists na hindi pa na-call, at yung called_at naman para sa mga na-call na pero hindi pa tinatanggap ng guidem ito yung magiging basehan ng display time sa lobby :)
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
            
            // sinisimulan lang yung timer kapag nasa unahan na ng queue yung tourist
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

// timer na magc-countdown kapag nasa unahan na ng queue yung tour guide, at kapag na-expire yung time, mare-refresh yung page para bumalik sa lobby view at mawala sa unahan ng queue
function startClaimTimer() {
    let timeLeft = 30;
    const timerDisplay = document.getElementById('selection-timer');
    
    if (claimTimerInterval) clearInterval(claimTimerInterval);

    claimTimerInterval = setInterval(async () => {
        timeLeft--;
        if (timerDisplay) timerDisplay.innerText = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(claimTimerInterval);
            
            // tiga sabi lang na na miss niya yung turn niya if nagexpire na yung timer
            try {
                await fetch('api/missed_turn.php', { method: 'POST' });
            } catch(e) {
                console.error("Could not process missed turn", e);
            }
            
            alert("Time is up! You have been moved to the back of the queue.");
            window.location.reload();
        }
    }, 1000);
}

// history stuffs
async function loadHistory() {
    try {
        const response = await fetch('api/get_history.php');
        const data = await response.json();
        const tableBody = document.getElementById('historyTable');

        if (data.success && data.history.length > 0) {
            historyTours = data.history; // tiga save sa global array para magamit sa pag-view ng receipt details kapag na-click yung history entry

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

// receipt stuffs
function viewHistoryReceipt(index) {
    const tour = historyTours[index];
    if (!tour) return console.error("History tour not found!");

    const modalBody = document.getElementById('tourist-receipt-content');
    if (!modalBody) return;

    const dateObj = new Date(tour.completed_at || Date.now());
    const formattedDate = dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) + ' ; ' + 
                          dateObj.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit' });

    const destinationsString = tour.destinations || 'No itineraries listed';
    const destinationsHTML = destinationsString.split(',').map(dest => `<span>${dest.trim()}</span>`).join('');

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
    const destinationsHTML = destinationsString.split(',').map(dest => `<span>${dest.trim()}</span>`).join('');

    // if nasa unahan na yung guide, yung function nito is ilalabas na yung accept button   
    let actionArea = '';
    if (typeof IS_QUEUE_NUMBER_ONE !== 'undefined' && IS_QUEUE_NUMBER_ONE) {
        actionArea = `
        <div style="display: flex; justify-content: flex-end; margin-top: 2rem;">
            <button onclick="acceptTour(${tourist.customer_id})" class="accept-btn" style="background-color: #109620; color: #ffffff; border: none; padding: 0.6rem 2.5rem; font-size: 1.1rem; font-weight: 00; font-family: 'Roboto Condensed', sans-serif; border-radius: 2px; cursor: pointer; transition: background-color 0.2s;">
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

        ${actionArea}
    `;

    const modalContainer = document.getElementById('tourist-receipt-overlay');
    if (modalContainer) modalContainer.style.display = 'flex';
}

function closeReceipt() {
    const modalOverlay = document.getElementById('tourist-receipt-overlay');
    if (modalOverlay) modalOverlay.style.display = 'none';
}

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

// auth stuffs
async function handleLogoutOnly() {
    try {
        const response = await fetch('../auth/log out/logout_tour_guide.php', { method: 'POST' }); 
        const result = await response.json();
        if (result.success) window.location.href = "../auth/log in/login_tour_guide.php";
    } catch (error) {
        console.error("Logout Error:", error);
    }
}

async function handleClockOut() {
    if (!confirm("Are you sure you want to clock out? You will lose your current place in the queue!")) return; 

    try {
        const response = await fetch('api/clock_out.php', { method: 'POST' });
        const result = await response.json();
        if (result.success) window.location.href = "../auth/log in/login_tour_guide.php";
    } catch (error) {
        console.error("Clock Out Error:", error);
    }
}

// para sa profile menu at iba pang initializations kapag nagload na yung page
document.addEventListener('DOMContentLoaded', () => {
    loadHistory();
    initWaitingLobby(); 
    startPolling();

    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

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