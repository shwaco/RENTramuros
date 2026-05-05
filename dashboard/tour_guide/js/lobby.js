// kinukuha yung waiting tourists from the server and ina-update yung lobby display
// ini-initialize yung tourist lobby section — kinukuha ang lahat ng Pending bookings at ginagawang clickable blocks para makita ng guide ang details ng bawat tourist
async function initWaitingLobby() {
    const lobbyContainer = document.getElementById('tourist-lobby');
    const idleStateText = document.getElementById('idle-state-text');

    if (!lobbyContainer) return;

    try {
        const response = await fetch('../../backend/api/ui/tourguide/get_waiting_tourists.php')
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            waitingTourists = result.data;

            if (idleStateText) idleStateText.style.display = 'none';

            // ginagawang clickable tourist blocks yung bawat waiting tourist
            lobbyContainer.innerHTML = waitingTourists.map(tourist => {
                const displayTime = tourist.booking_date || tourist.booking_time;
                let timeString = "00:00";
                let dateString = "00/00/00";

                if (displayTime) {
                    const dateObj = new Date(displayTime.replace(/-/g, '/'));
                    if (!isNaN(dateObj.getTime())) {
                        timeString = dateObj.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                        dateString = dateObj.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: '2-digit' });
                    }
                }

                return `
                    <div class="t-block" onclick="viewTouristDetails(${tourist.booking_request_id})">
                        <div class="t-block-left">${tourist.booking_request_id}</div>
                        <div class="t-block-right">
                            <span style="font-weight:bold; color:#000;">${dateString}</span>
                            <span>${timeString}</span>
                        </div>
                    </div>
                `;
            }).join('');

            // kung #1 na yung guide sa queue, sisimulan na yung claim timer niya
            if (typeof IS_QUEUE_NUMBER_ONE !== 'undefined' && IS_QUEUE_NUMBER_ONE) {
                startClaimTimer();
            }
        } else {
            // kung walang tourists na naghihintay, pinapakita nito yung empty state
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