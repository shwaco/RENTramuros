// lahat ng queue-related na actions ng guide: clock in, join queue, polling, at claim timer

// nagsesend ng POST request sa clock_in API — nire-reload ang page kapag successful
// para makita ng guide yung updated na dashboard state niya
async function clockIn() {
    try {
        const res = await fetch('../../../backend/api/actions/tour_guide/clock_in.php', { method: 'POST' });
        const data = await res.json();
        if (data.success) location.reload();
    } catch (e) {
        console.error("Error clocking in", e);
    }
}

// nagsesend ng POST request sa join_queue API — kapag successful, nire-reload ang page
// para lumipat sa Queuing view na may queue number display
async function joinQueue() {
    try {
        const res = await fetch('../../../backend/api/actions/tour_guide/join_queue.php', { method: 'POST' });
        const data = await res.json();
        if (data.success) location.reload();
    } catch (e) {
        console.error("Error joining queue", e);
    }
}

// Polls every 5 seconds for status/position changes.
// Also runs when On Tour so the guide's page reloads automatically
// when the tourist marks the booking as Done or Cancelled.
function startPolling() {
    setInterval(async () => {
        try {
            const response = await fetch('../../../backend/logics/check_queue.php');
            const data = await response.json();

            if (!data.success) return;

            // Reload on any status change from what PHP rendered on load
            if (data.status !== CURRENT_GUIDE_STATUS) {
                window.location.reload();
                return;
            }

            // Also reload if guide is Queuing and position changed
            if (data.status === 'Queuing') {
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

function startClaimTimer() {
    let timeLeft = 30;
    const timerDisplay = document.getElementById('selection-timer');

    if (claimTimerInterval) clearInterval(claimTimerInterval);

    claimTimerInterval = setInterval(async () => {
        timeLeft--;
        if (timerDisplay) timerDisplay.innerText = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(claimTimerInterval);
            try {
                await fetch('../../../backend/logics/missed_turn.php', { method: 'POST' });
            } catch (e) {
                console.error("Could not process missed turn", e);
            }
            alert("Time is up! You have been moved to the back of the queue.");
            window.location.reload();
        }
    }, 1000);
}