// para mag laod yung history
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
    
// tiga mark  ng tour as completed tas binabalik yung tour guide sa queue
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

// para magrefresh yung page every 10 seconds
function startPolling() {
    setInterval(() => {
        if (!document.getElementById('active-tour-card')) {
            location.reload();
        }
    }, 10000);
}

document.addEventListener('DOMContentLoaded', () => {
    loadActiveTour();
    loadHistory();
    startPolling();
});

// 1. SAFE EXIT: Logs out but saves their place in the queue
async function handleLogoutOnly() {
    try {
        // Correct path based on your folder structure!
        const response = await fetch('../auth/log out/logout_tour_guide.php', { method: 'POST' }); 
        const result = await response.json();
        if (result.success) {
            window.location.href = "../auth/log in/login_tour_guide.php";
        }
    } catch (error) {
        console.error("Logout Error:", error);
    }
}

// 2. END SHIFT: Removes them from the queue entirely
async function handleClockOut() {
    if (!confirm("Are you sure you want to clock out? You will lose your current place in the queue!")) {
        return; 
    }

    try {
        // API folder is right next to js folder inside queue-management-system
        const response = await fetch('api/clock_out.php', { method: 'POST' });
        const result = await response.json();
        if (result.success) {
            window.location.href = "../auth/log in/login_tour_guide.php";
        }
    } catch (error) {
        console.error("Clock Out Error:", error);
    }
}

async function loadActiveTour() {
    try {
        const response = await fetch('api/get_tour_details.php');
        const result = await response.json();
        
        // the div in html where the active tour will show up
        const container = document.getElementById('activeTourContainer');
        if (!container) return;

        if (result.success && result.assigned && result.data) {
            const tour = result.data;
            // kapag may naassign na tourist, ipapakita yung active card
            container.innerHTML = `
                <div id="active-tour-card" class="bg-white p-6 rounded shadow-md border-l-4 border-red-800 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-2"><i class="fas fa-bell text-yellow-500 mr-2"></i>New Tourist Assigned!</h2>
                    <div class="mb-4 grid grid-cols-2 gap-2 text-sm">
                        <p><strong>Tourist Name:</strong> ${tour.first_name} ${tour.last_name}</p>
                        <p><strong>Queue Number:</strong> <span class="text-red-800 font-bold text-lg">${tour.queue_number || 'N/A'}</span></p>
                        <p><strong>Vehicle:</strong> ${tour.vehicle_type || 'Standard Kalesa'}</p>
                        <p><strong>Package:</strong> ${tour.package_name || 'Custom Route'}</p>
                        <p class="col-span-2"><strong>Destinations:</strong> ${tour.destinations || 'Pending'}</p>
                    </div>
                    <button onclick="finishTour(${tour.customer_id})" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition w-full font-bold">
                        <i class="fas fa-check-circle mr-2"></i>Complete Tour
                    </button>
                </div>
            `;
        } else {
            // Wala pang tourist. then ipapakita yung waiting"
            container.innerHTML = `
                <div class="idle-state">
                    <div class="idle-icon-wrap"><i class="fas fa-hourglass-half animate-pulse"></i></div>
                    <h2 class="welcome-text">Welcome, ${typeof guideName !== 'undefined' ? guideName : 'Guide'}!</h2>
                    <h3 class="idle-title">You are #${typeof queuePosition !== 'undefined' ? queuePosition : '...'} in line</h3>
                    <p class="text-sm text-gray-500 mt-4 font-bold text-center">Waiting for tourists...</p>
                </div>
            `;  
        }
    } catch (e) {
        console.error("Failed to load active tour:", e);
    }
}