// asset/js/dashboard_stats.js

const CHART_API_URL = 'backend.v2/get_chart_data.php';
const STATS_API_URL = 'backend.v2/get_dashboard_stats.php';

document.addEventListener('DOMContentLoaded', async function() {
    
    try {
        const statsRes = await fetch(STATS_API_URL);
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

function renderCharts(data) {
    // ==========================================
    // 1. PIE CHART: Visits per Attraction
    // ==========================================
    const pieCanvas = document.getElementById('visitsPieChart');
    if (pieCanvas && data.pie_chart.labels.length > 0) {
        new Chart(pieCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: data.pie_chart.labels, // Dynamic Labels!
                datasets: [{
                    data: data.pie_chart.values, // Dynamic Data!
                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EF4444', '#EC4899'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right' } }
            }
        });
    }

    // ==========================================
    // 2. BAR CHART: Packages Availed
    // ==========================================
    const barCanvas = document.getElementById('packagesBarChart');
    if (barCanvas && data.bar_chart.labels.length > 0) {
        new Chart(barCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: data.bar_chart.labels, // Dynamic Labels!
                datasets: [{
                    label: 'Bookings',
                    data: data.bar_chart.values, // Dynamic Data!
                    backgroundColor: 'rgba(139, 92, 246, 0.7)',
                    borderColor: 'rgb(139, 92, 246)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // ==========================================
    // 3. LINE CHART: Packages vs Attractions
    // ==========================================
    const lineCanvas = document.getElementById('trendLineChart');
    if (lineCanvas && data.line_chart.dates.length > 0) {
        new Chart(lineCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: data.line_chart.dates, // Dynamic Dates!
                datasets: [
                    {
                        label: 'Package Bookings',
                        data: data.line_chart.packages, // Dynamic Data!
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Attraction Only Bookings',
                        data: data.line_chart.attractions, // Dynamic Data!
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: { y: { beginAtZero: true, suggestedMax: 10 } }
            }
        });
    }
}