<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Upcoming Events - RENTramuros</title>
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="asset/css/admin.css">
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
                        <a href="manage_guides.php" class="text-gray-300 font-medium px-1 py-2 hover:text-white transition duration-150">
                            Guides
                        </a>
                        <!-- Active Link (Events) -->
                        <a href="manage_events.php" class="text-white font-semibold px-1 py-2 border-b-2 border-blue-400 hover:text-blue-300 transition duration-150">
                            Events
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

    <main class="container mx-auto px-4 py-8 flex-grow">
        
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 card-hover">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-list-ul mr-2 text-blue-500"></i>Active Events
                </h2>
                <button onclick="openAddEventModal()" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i>Add New Event
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-600 uppercase text-xs tracking-wider">
                            <th class="px-4 py-3 font-bold border-b">ID</th>
                            <th class="px-4 py-3 font-bold border-b">Event Name</th>
                            <th class="px-4 py-3 font-bold border-b">Date</th>
                            <th class="px-4 py-3 font-bold border-b">Time</th>
                            <th class="px-4 py-3 font-bold border-b">Location</th>
                            <th class="px-4 py-3 font-bold border-b">Image</th>
                            <th class="px-4 py-3 font-bold border-b text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="events-table-body" class="text-gray-700 divide-y divide-gray-100">
                        <!-- Populated via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- ===================================== -->
    <!--           ADD EVENT MODAL             -->
    <!-- ===================================== -->
    <div id="add-event-modal" class="hidden fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto relative">
        
            <div class="flex justify-between items-center mb-6">
                <h2 id="modal-title" class="text-xl font-bold text-gray-800">
                    <i class="fas fa-calendar-plus mr-2"></i>Create Event
                </h2>
                <button onclick="closeAddEventModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
        
            <form id="add-event-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Event Name</label>
                    <input type="text" id="event-name" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Date</label>
                    <input type="text" id="event-date" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Time</label>
                    <input type="text" id="event-time" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Location</label>
                    <input type="text" id="event-location" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Filename</label>
                    <input type="text" id="event-img" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeAddEventModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300 transition">Cancel</button>
                    <button type="submit" id="submit-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">Save Event</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================== -->
    <!--           EDIT EVENT MODAL            -->
    <!-- ===================================== -->
    <div id="edit-event-modal" class="hidden fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto relative">
        
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-edit mr-2"></i>Edit Event
                </h2>
                <button onclick="document.getElementById('edit-event-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        
            <form id="edit-event-form" class="space-y-4">
                <input type="hidden" id="edit-event-id">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Event Name</label>
                    <input type="text" id="edit-event-name" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Date</label>
                    <input type="text" id="edit-event-date" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Time</label>
                    <input type="text" id="edit-event-time" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Location</label>
                    <input type="text" id="edit-event-location" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Image Link/Filename</label>
                    <input type="text" id="edit-event-img" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div> 

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="document.getElementById('edit-event-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="w-full text-center py-6 mt-auto text-sm text-gray-500">
        &copy; 2026 RENTramuros. All rights reserved.
    </footer>

    <script src="asset/js/events_management.js"></script>
    <script>
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