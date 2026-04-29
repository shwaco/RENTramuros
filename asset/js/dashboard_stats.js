// js/dashboard_stats.js

console.log("Dashboard Stats module loaded successfully!");

// We will build the logic to fetch the top 4 card numbers here later!
// js/dashboard_stats.js

// 1. Point this to exactly where you saved the PHP file above!
const STATS_API_URL = 'http://localhost/RENTramuros/backend.v2/get_dashboard_stats.php'; 

async function loadDashboardStats() {
    try {
        const response = await fetch(STATS_API_URL);
        const json = await response.json();

        if (json.status === "success") {
            const stats = json.data;
            
            // 2. Target your HTML IDs and inject the database numbers!
            document.getElementById('waiting-count').innerText = stats.pending;
            document.getElementById('serving-count').innerText = stats.accepted;
            document.getElementById('completed-count').innerText = stats.on_tour;
            document.getElementById('today-count').innerText = stats.completed;
            
        } else {
            console.error("Stats API Error:", json.message);
        }
    } catch (error) {
        console.error("Failed to fetch dashboard stats:", error);
    }
}

// 3. Run this as soon as the page loads!
document.addEventListener('DOMContentLoaded', loadDashboardStats);