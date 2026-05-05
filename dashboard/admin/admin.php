<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add this in your <head> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RENTramuros</title>
    
    <!-- External Styling -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="asset/css/admin.css">
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- Header Navigation -->
    <header class="bg-gradient-to-r from-gray-700 via-gray-600 to-gray-700 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Left Side: Logo & Branding -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="admin.php" class="flex items-center text-white hover:opacity-80 transition duration-150">
                        <i class="fas fa-chess-rook text-2xl mr-2"></i>
                        <span class="font-bold text-xl tracking-wide">RENTramuros</span>
                        <span class="text-gray-300 font-light text-xl mx-2">|</span>
                        <span class="text-gray-300 font-light text-lg">Admin Hub</span>
                    </a>
                </div>

                <!-- Middle/Right Side: Navigation & User Controls -->
                <div class="flex items-center space-x-6">
                    
                    <!-- Main Nav Links -->
                    <nav class="flex space-x-6">
                        <a href="admin.php" class="text-gray-300 font-medium px-1 py-2 hover:text-white transition duration-150">
                            Dashboard
                        </a>
                        <a href="manage_guides.php" class="text-gray-300 font-medium px-1 py-2 hover:text-white transition duration-150">
                            Guides
                        </a>
                        <!-- Active Link (Events) -->
                        <a href="manage_events.php" class="text-white font-semibold px-1 py-2 border-b-2 border-blue-400 hover:text-blue-300 transition duration-150">
                            Events
                        </a>
                        <a href="manage_attractions.php" class="text-white font-semibold px-1 py-2 border-b-2 border-blue-400 hover:text-blue-300 transition duration-150">
                            Attractions
                        </a>
                    </nav>

                    <!-- Divider -->
                    <div class="h-6 w-px bg-gray-500 mx-2"></div>

                    <!-- User Controls -->
                    <div class="flex items-center space-x-4">
                        <div id="current-time" class="text-gray-200 text-sm font-mono hidden md:block mr-2"></div>
                        <span class="text-gray-200 text-sm">Welcome, Admin</span>
                        
                        <a href="../../logout-api.php" class="text-red-400 hover:text-red-300 font-semibold flex items-center transition duration-150">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="container mx-auto px-4 py-8 flex-grow">
        
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Overview Statistics</h2>
            <p class="text-gray-500 text-sm">Live data from the RENTramuros database</p>
        </div>

        <!-- Analytics Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            
            <!-- Pie Chart: Visits per Attraction -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 card-hover">
                <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-chart-pie text-blue-500 mr-2"></i>Visits per Attraction</h3>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="visitsPieChart"></canvas>
                </div>
            </div>

            <!-- Bar Graph: Packages Availed -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 card-hover">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-chart-bar text-purple-500 mr-2"></i>Packages Availed</h3>
                    <select id="package-filter" class="text-sm border border-gray-300 rounded-md px-2 py-1 outline-none focus:ring-2 focus:ring-purple-500 text-gray-600">
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="packagesBarChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Line Graph: Packages vs Attractions Bookings -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 mb-12 card-hover">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-chart-line text-green-500 mr-2"></i>Booking Trends: Packages vs Attractions</h3>
                <select id="trend-filter" class="text-sm border border-gray-300 rounded-md px-2 py-1 outline-none focus:ring-2 focus:ring-green-500 text-gray-600">
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
            <div class="relative h-80 w-full">
                <canvas id="trendLineChart"></canvas>
            </div>
        </div>

        <!-- System Modules / Navigation Links -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6">System Modules</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Link to Guides -->
            <a href="manage_guides.php" class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:border-blue-300 hover:shadow-lg transition group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas fa-id-badge text-xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-300 group-hover:text-blue-500 transition"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Tour Guides</h3>
                <p class="text-gray-500 text-sm mt-1">Manage guide accounts, track statuses, and view touring history.</p>
            </a>

            <!-- Link to Events -->
            <a href="manage_events.php" class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:border-purple-300 hover:shadow-lg transition group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition">
                        <i class="fas fa-calendar-alt text-xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-300 group-hover:text-purple-500 transition"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Upcoming Events</h3>
                <p class="text-gray-500 text-sm mt-1">Add, update, and manage the schedule for Intramuros events.</p>
            </a>

            <!-- Future Link to Attractions -->
            <a href="manage_attractions.php" class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:border-green-300 hover:shadow-lg transition group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-50 text-green-600 rounded-lg group-hover:bg-green-600 group-hover:text-white transition">
                        <i class="fas fa-archway text-xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-300 group-hover:text-green-500 transition"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Attractions</h3>
                <p class="text-gray-500 text-sm mt-1">Manage historical sites, museum details, and entrance fees.</p>
            </a>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full text-center py-6 mt-auto text-sm text-gray-500">
        &copy; 2026 RENTramuros. All rights reserved.
    </footer>

    <!-- JavaScript to load the top stats -->
    <script src="asset/js/dashboard_stats.js"></script>
</body>
</html>