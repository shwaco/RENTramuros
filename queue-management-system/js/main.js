/**
 * Loads the tour history into the table
 */
async function loadHistory() {
    try {
        const response = await fetch('api/get_history.php');
        const data = await response.json();
        const tableBody = document.getElementById('historyTable');

        if (data.success && data.history.length > 0) {
            tableBody.innerHTML = data.history.map(tour => `
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="p-4 font-bold text-red-800">${tour.queue_number || '---'}</td>
                    <td class="p-4">${tour.first_name} ${tour.last_name}</td>
                    <td class="p-4 text-sm">${new Date(tour.completed_at).toLocaleString()}</td>
                    <td class="p-4">${tour.vehicle_type}</td>
                    <td class="p-4"><span class="badge-green"><i class="fas fa-check mr-1"></i>Completed</span></td>
                </tr>
            `).join('');
        } else {
            tableBody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-gray-400 italic">No history available yet.</td></tr>';
        }
    } catch (e) {
        console.error("Failed to load history:", e);
    }
}

/**
 * Marks the tour as completed and puts the guide back in the queue
 */
async function finishTour(customerId) {
    if (!customerId) {
        alert("Error: Could not find the Tourist ID.");
        return;
    }

    if (!confirm("Are you sure the tour is finished? You will be placed at the back of the queue.")) {
        return; 
    }

    try {
        const response = await fetch('api/complete_tour.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ customer_id: customerId }) 
        });
        
        // If the server returned an error (like a PHP notice), this line will fail
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

/**
 * Auto-refresh the page every 10 seconds to check for new assignments or queue changes
 */
function startPolling() {
    setInterval(() => {
        // If we are currently on the "Idle/Waiting" screen, refresh to see if assigned
        if (!document.getElementById('active-tour-card')) {
            location.reload();
        }
    }, 10000);
}

// Initialize everything on page load
document.addEventListener('DOMContentLoaded', () => {
    loadHistory();
    startPolling();
});