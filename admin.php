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
    <!-- Main Header -->
    <header class="bg-[#7a3229] text-white py-4 shadow-md w-full">
        <div class="container mx-auto px-6 flex justify-between items-center">
            
            <!-- Left Side: Menu Icon & Logo -->
            <div class="flex items-center space-x-6">
                <button class="text-white hover:text-gray-300 focus:outline-none transition">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="flex items-center space-x-2 text-xl tracking-wide cursor-pointer">
                    <i class="far fa-image text-2xl"></i> <!-- Placeholder for your logo image -->
                    <span class="font-bold">RENT<span class="font-light">ramuros</span></span>
                </div>
            </div>

            <!-- Right Side: Navigation Links & Profile -->
            <nav class="flex items-center space-x-8 text-sm font-medium">
                <a href="#" class="hover:text-gray-300 transition">Map</a>
                <a href="#" class="hover:text-gray-300 transition">Tours</a>
                <a href="#" class="hover:text-gray-300 transition">My Bookings</a>
                
                <!-- Profile Avatar Placeholder -->
                <div class="w-8 h-8 bg-gray-200 rounded-full cursor-pointer hover:bg-gray-300 transition"></div>
            </nav>
            
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
    <!-- Main Footer -->
    <footer class="bg-[#7a3229] text-white pt-12 pb-6 mt-auto w-full">
        <div class="container mx-auto px-6">
            
            <!-- Top Section: Logo, Socials, and Links -->
            <div class="flex flex-col md:flex-row justify-between mb-10">
                
                <!-- Left Side: Logo & Social Icons -->
                <div class="mb-8 md:mb-0">
                    <div class="flex items-center space-x-2 text-2xl mb-4">
                        <i class="far fa-image text-3xl"></i>
                        <span class="font-bold">RENT<span class="font-light">ramuros</span></span>
                    </div>
                    <!-- Social Media Icons -->
                    <div class="flex space-x-5 mt-2">
                        <a href="#" class="text-white hover:text-gray-300 text-lg transition"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white hover:text-gray-300 text-lg transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white hover:text-gray-300 text-lg transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white hover:text-gray-300 text-lg transition"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>

                <!-- Right Side: Info Columns -->
                <div class="flex space-x-16 md:pr-12">
                    <!-- Contact Column -->
                    <div>
                        <h4 class="font-bold mb-2 text-sm uppercase tracking-wider">Contact</h4>
                        <p class="text-xs text-gray-300">Company Information</p>
                    </div>

                    <!-- About Column -->
                    <div>
                        <h4 class="font-bold mb-2 text-sm uppercase tracking-wider">About</h4>
                        <p class="text-xs text-gray-300">Company Information</p>
                    </div>

                    <!-- Support Column -->
                    <div>
                        <h4 class="font-bold mb-2 text-sm uppercase tracking-wider">Support</h4>
                        <p class="text-xs text-gray-300">Company Information</p>
                    </div>
                </div>
                
            </div>

            <!-- Bottom Section: Copyright -->
            <div class="flex justify-end items-center text-xs text-gray-300 mt-4">
                <p>All right reserved. Copyright &copy; RENTramuros Manila.</p>
            </div>
            
        </div>
    </footer>

    <!-- JavaScript to load the top stats -->
    <script src="asset/js/dashboard_stats.js"></script>
</body>
</html>