<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics Overview - RENTramuros</title>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="flex h-screen bg-gray-50 antialiased overflow-hidden">

    <aside class="relative h-screen bg-gray-800 text-white flex flex-col shadow-2xl transition-all duration-300 w-20 hover:w-64 overflow-hidden group hidden md:flex shrink-0 z-50">
        
        <div class="flex items-center h-20 px-4 border-b border-gray-700 whitespace-nowrap">
            <div class="w-12 flex justify-center shrink-0">
                <i class="fas fa-chess-rook text-3xl text-gray-300"></i>
            </div>
            <div class="ml-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                <div class="font-bold text-xl tracking-wide">RENT<span class="font-light">ramuros</span></div>
                <div class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Admin Hub</div>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-3 overflow-y-auto overflow-x-hidden">
            
            <a href="admin.php" class="flex items-center py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition whitespace-nowrap">
                <div class="w-12 flex justify-center shrink-0"><i class="fas fa-home text-xl"></i></div>
                <span class="ml-3 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Home Page</span>
            </a>
            
            <a href="manage_guides.php" class="flex items-center py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition whitespace-nowrap">
                <div class="w-12 flex justify-center shrink-0"><i class="fas fa-users text-xl"></i></div>
                <span class="ml-3 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Guides</span>
            </a>
            
            <a href="manage_events.php" class="flex items-center py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition whitespace-nowrap">
                <div class="w-12 flex justify-center shrink-0"><i class="fas fa-calendar-alt text-xl"></i></div>
                <span class="ml-3 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Events</span>
            </a>
            
            <a href="manage_attractions.php" class="flex items-center py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition whitespace-nowrap">
                <div class="w-12 flex justify-center shrink-0"><i class="fas fa-archway text-xl"></i></div>
                <span class="ml-3 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Attractions</span>
            </a>
            
            <a href="statistics_overview.php" class="flex items-center py-3 bg-[#7a3229] text-white rounded-lg shadow-md transition whitespace-nowrap">
                <div class="w-12 flex justify-center shrink-0"><i class="fas fa-chart-pie text-xl"></i></div>
                <span class="ml-3 font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300">Statistics</span>
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative bg-gray-50">
        
        <header class="bg-white shadow-sm py-4 px-6 flex justify-between items-center z-10 border-b border-gray-200 shrink-0">
            <button class="md:hidden text-gray-600 focus:outline-none hover:text-[#7a3229]">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <div class="hidden md:block"></div>

            <div class="flex items-center space-x-6">
                <div id="current-time" class="text-gray-500 text-sm font-mono hidden md:block"></div>
                <div class="text-sm font-semibold text-gray-700 border-l pl-6 border-gray-300">
                    Welcome, Admin
                </div>
                <a href="../../logout-api.php" class="text-red-500 hover:text-red-700 font-bold transition flex items-center text-sm">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 flex flex-col">
            
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-chart-pie mr-2 text-[#7a3229]"></i>Statistics Overview
                </h2>
                <p class="text-gray-500 text-sm mt-1">Live data from the RENTramuros database</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 card-hover">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-map-marker-alt text-[#7a3229] mr-2"></i>Visits per Attraction
                    </h3>
                    <div class="relative h-64 w-full flex justify-center">
                        <canvas id="visitsPieChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 card-hover">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-lg font-bold text-gray-800">
                            <i class="fas fa-box-open text-[#7a3229] mr-2"></i>Packages Availed
                        </h3>
                        <select id="package-filter" class="text-sm border border-gray-300 rounded-md px-2 py-1 outline-none focus:ring-2 focus:ring-[#7a3229] text-gray-600 shadow-sm">
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select>
                    </div>
                    <div class="relative h-64 w-full">
                        <canvas id="packagesBarChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 mb-8 card-hover">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-chart-line text-[#7a3229] mr-2"></i>Booking Trends: Packages vs Attractions
                    </h3>
                    <select id="trend-filter" class="text-sm border border-gray-300 rounded-md px-2 py-1 outline-none focus:ring-2 focus:ring-[#7a3229] text-gray-600 shadow-sm">
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
                <div class="relative h-80 w-full">
                    <canvas id="trendLineChart"></canvas>
                </div>
            </div>
            
        </main>

        <footer class="bg-white border-t border-gray-200 w-full text-center py-4 text-sm text-gray-500 shrink-0">
            &copy; 2026 RENTramuros. All rights reserved.
        </footer>
    </div>

    <script src="js/chart_stats.js"></script>
    <script>
        // Keep your clock running on this page too!
        function updateClock() {
            const timeEl = document.getElementById('current-time');
            if(timeEl) {
                const now = new Date();
                timeEl.textContent = now.toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>