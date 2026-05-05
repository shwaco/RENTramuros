const STATUS_API_URL = 'backend/api/analytics/get_status_data.php';

document.addEventListener('DOMContentLoaded', async function() {
    
    try {
        const statsRes = await fetch(STATUS_API_URL);
        const statsJson = await statsRes.json();

        if (statsJson.status === "success") {
            // Note: Mapping to the exact IDs currently in your HTML
            document.getElementById('waiting-count').textContent = statsJson.data.pending;
            document.getElementById('serving-count').textContent = statsJson.data.accepted;
            document.getElementById('completed-count').textContent = statsJson.data.on_tour; // This is the "On Tours" card
            document.getElementById('today-count').textContent = statsJson.data.completed;   // This is the "Completed Tours" card
        } else {
            console.error("Stats API Error:", statsJson.message);
        }
    } catch (error) {
        console.error("Failed to load top stats:", error);
    }

    try {
        const response = await fetch(CHART_API_URL);
        const json = await response.json();

        if (json.status === "success") {
            const apiData = json.data;
            renderCharts(apiData);
        } else {
            console.error("Chart API Error:", json.message);
        }
    } catch (error) {
        console.error("Failed to load chart data:", error);
    }
});