<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Queuing System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./asset/css/admin.css">
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- Admin Hub Navbar -->
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
                        
                        <!-- Active Link (Guides) -->
                        <a href="manage_guides.php" class="text-white font-semibold px-1 py-2 border-b-2 border-blue-400 hover:text-blue-300 transition duration-150">
                            Guides
                        </a>
                        
                        <a href="manage_events.php" class="text-gray-300 font-medium px-1 py-2 hover:text-white transition duration-150">
                            Events
                        </a>
                    </nav>

                    <!-- Divider -->
                    <div class="h-6 w-px bg-gray-500 mx-2"></div>

                    <!-- User Controls -->
                    <div class="flex items-center space-x-4">
                        <!-- Re-added your live clock here to keep the functionality! -->
                        <div id="current-time" class="text-gray-200 text-sm font-mono hidden md:block mr-2"></div>
                        <span class="text-gray-200 text-sm">Welcome, Admin</span>
                        
                        <a href="logout.php" class="text-red-400 hover:text-red-300 font-semibold flex items-center transition duration-150">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800" id="waiting-count">0</h3>
                        <p class="text-gray-600">Pending Bookings</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <i class="fas fa-user-check text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800" id="serving-count">0</h3>
                        <p class="text-gray-600">Accepted Bookings</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i class="fas fa-horse text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800" id="completed-count">0</h3>
                        <p class="text-gray-600">On Tours</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800" id="today-count">0</h3>
                        <p class="text-gray-600">Completed Tours</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Add Tour Guide Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 card-hover">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-plus-circle mr-2"></i>Add New Tour Guide
                    </h2>

                    <form id="add-guide-form" class="space-y-4" novalidate>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" id="guide-fname" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Juan">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" id="guide-lname" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="dela Cruz">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="guide-email" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="juan@rentramuros.ph">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" id="guide-password" required minlength="8"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Min. 8 characters">
                        </div>

                        <!-- Inline feedback for add form -->
                        <div id="add-guide-feedback" class="hidden text-sm px-3 py-2 rounded-lg"></div>

                        <button type="submit" id="add-guide-btn"
                                class="w-full bg-gray-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-gray-700 transition">
                            <i class="fas fa-user-plus mr-2"></i>Create Guide Account
                        </button>
                    </form>
                </div>
            </div>

            <!-- Guide Table Panel -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6 card-hover">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-list mr-2"></i>Tour Guide Management
                        </h2>
                        <div class="flex space-x-2">
                            <button onclick="loadTourGuides()"
                                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                                <i class="fas fa-sync-alt mr-2"></i>Refresh
                            </button>
                        </div>
                    </div>

                    <!-- On Tour Cards — dynamically populated by JS -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">On Tour</h3>
                        <div id="onTourCards" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <p id="no-tour-msg" class="text-sm text-gray-400 italic col-span-3">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Loading...
                            </p>
                        </div>
                    </div>

                    <!-- Guide Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto whitespace-nowrap">
                            <thead>
                                <tr class="bg-gray-50 text-left border-b">
                                    <th class="px-3 py-3 text-sm font-medium text-gray-700">guide_id</th>
                                    <th class="px-3 py-3 text-sm font-medium text-gray-700">first_name</th>
                                    <th class="px-3 py-3 text-sm font-medium text-gray-700">last_name</th>
                                    <th class="px-3 py-3 text-sm font-medium text-gray-700">email</th>
                                    <th class="px-3 py-3 text-sm font-medium text-gray-700">current_status</th>
                                    <th class="px-3 py-3 text-sm font-medium text-gray-700">last_active_at</th>
                                    <th class="px-3 py-3 text-sm font-medium text-gray-700">last_dispatch_time</th>
                                    <th class="px-3 py-3 text-sm font-medium text-gray-700">became_available_at</th>
                                    <th class="px-3 py-3 text-sm font-medium text-gray-700 text-center">current_tourist_id</th>
                                    <th class="px-3 py-3 text-sm font-medium text-gray-700 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="guide-table-body" class="divide-y divide-gray-200">
                                <tr>
                                    <td colspan="10" class="px-3 py-6 text-center text-gray-400 italic">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>Loading guides...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex justify-center items-center">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-edit mr-2"></i>Edit Tour Guide
            </h2>

            <form id="edit-guide-form" class="space-y-4" novalidate>
                <input type="hidden" id="edit-guide-id">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" id="edit-fname" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" id="edit-lname" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="edit-email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        New Password <span class="text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <input type="password" id="edit-password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Leave blank to keep current password">
                </div>

                <div id="edit-guide-feedback" class="hidden text-sm px-3 py-2 rounded-lg"></div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" id="edit-guide-btn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="toast" class="toast hidden">
        <i id="toast-icon"></i>
        <span id="toast-msg"></span>
    </div>

    <footer class="bg-gray-400 text-white py-6 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; RENTramuros. All rights reserved.</p>
        </div>
    </footer>

    <script src="asset/js/dashboard_stats.js"></script>
    <script src="asset/js/guide_management.js"></script>
    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('current-time').textContent =
                now.toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>