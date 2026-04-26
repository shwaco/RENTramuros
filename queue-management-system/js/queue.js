// lahat ng queue-related na actions ng guide: clock in, join queue, polling, at claim timer

async function clockIn() {
    try {
        const res = await fetch('api/clock_in.php', { method: 'POST' });
        const data = await res.json();
        // kung successful, i-reload na yung page para ma-update yung UI sa bagong status
        if (data.success) location.reload();
    } catch (e) {
        console.error("Error clocking in", e);
    }
}

async function joinQueue() {
    try {
        const res = await fetch('api/join_queue.php', { method: 'POST' });
        const data = await res.json();
        // i-reload para makita na ng guide yung queue position niya
        if (data.success) location.reload();
    } catch (e) {
        console.error("Error joining queue", e);
    }
}

// regular polling para i-check sa server kung may updates sa queue position ng guide
function startPolling() {
    setInterval(async () => {
        // kung On Tour na yung guide, walang reason na mag-poll pa para sa position
        if (typeof CURRENT_GUIDE_STATUS !== 'undefined' && CURRENT_GUIDE_STATUS === 'On Tour') return;

        try {
            const response = await fetch('api/check_queue.php');
            const data = await response.json();

            if (data.success && data.status === 'Queuing') {
                
                // kung hindi pa naka-set yung currentQueuePosition, ibabase sa PHP-injected na value
                if (typeof window.currentQueuePosition === 'undefined') {
                    window.currentQueuePosition = typeof queuePosition !== 'undefined' ? queuePosition : null;
                }

                // kung nagbago yung position ng guide sa queue, mag-reload para ma-update yung display
                if (window.currentQueuePosition !== null && window.currentQueuePosition !== data.position) {
                    window.location.reload();
                }
                
                // ina-update yung global state para sa susunod na poll
                window.currentQueuePosition = data.position;
            }
        } catch (e) {
            console.error("Radar error:", e);
        }
    }, 5000);
}

// 15-second timer para sa guide na nangunguna sa queue
function startClaimTimer() {
    let timeLeft = 15;
    const timerDisplay = document.getElementById('selection-timer');

    // iclear muna yung existing timer kung meron para hindi magdoble
    if (typeof claimTimerInterval !== 'undefined' && claimTimerInterval) {
        clearInterval(claimTimerInterval);
    }

    claimTimerInterval = setInterval(async () => {
        timeLeft--;
        if (timerDisplay) timerDisplay.innerText = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(claimTimerInterval);

            try {
                // kapag nag-expire yung timer, ipupush yung guide sa likod ng queue
                await fetch('api/missed_turn.php', { method: 'POST' });
            } catch (e) {
                console.error("Could not process missed turn", e);
            }

            alert("Time is up! You have been moved to the back of the queue.");
            window.location.reload();
        }
    }, 1000);
}