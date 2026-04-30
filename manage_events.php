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

    <header class="gradient-bg text-white shadow-lg py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-calendar-alt text-2xl"></i>
                <h1 class="text-xl font-bold tracking-tight">RENTramuros <span class="font-light text-gray-300">| Events</span></h1>
            </div>
            <nav class="flex items-center space-x-4">
                <a href="admin.php" class="hover:text-blue-300 transition text-sm font-semibold">Dashboard</a>
                <span class="text-sm <!DOCTYPE html>
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

    <header class="gradient-bg text-white shadow-lg py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-calendar-alt text-2xl"></i>
                <h1 class="text-xl font-bold tracking-tight">RENTramuros <span class="font-light text-gray-300">| Events</span></h1>
            </div>
            <nav class="flex items-center space-x-4">
                <a href="admin.php" class="hover:text-blue-300 transition text-sm font-semibold">Dashboard</a>
                <span class="text-sm border-l border-gray-500 pl-4 text-gray-300">Admin Mode</span>
            </nav>
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
                        </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="add-event-modal" class="hidden fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <!-- Adjusted width, padding, and rounding to match manage_guides -->
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto relative">
        
            <div class="flex justify-between items-center mb-6">
                <h2 id="modal-title" class="text-xl font-bold text-gray-800">
                    <i class="fas fa-edit mr-2"></i>Create Event
                </h2>
                <button onclick="closeAddEventModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
        
            <form id="add-event-form" class="space-y-4" data-mode="add">
                <input type="hidden" id="event-id">
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
                    <!-- Fixed ID here -->
                    <input type="text" id="event-location" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Image Link/Filename</label>
                    <input type="text" id="event-img" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div> <!-- Fixed missing closing div here! -->

                <div class="flex justify-end space-x-3 mt-6">
                    <!-- Updated button styling to match manage_guides -->
                    <button type="button" onclick="closeAddEventModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300 transition">Cancel</button>
                    <button type="submit" id="submit-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">Save Event</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Removed the container wrapper and gray background block to make it full width and subtle -->
    <footer class="w-full text-center py-6 mt-auto text-sm text-gray-500">
        &copy; 2026 RENTramuros. All rights reserved.
    </footer>

    <script src="asset/js/events_management.js"></script>
</body>
</html>border-l border-gray-500 pl-4 text-gray-300">Admin Mode</span>
            </nav>
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
                        </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="add-event-modal" class="hidden fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <!-- Adjusted width, padding, and rounding to match manage_guides -->
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto relative">
        
            <div class="flex justify-between items-center mb-6">
                <h2 id="modal-title" class="text-xl font-bold text-gray-800">
                    <i class="fas fa-edit mr-2"></i>Create Event
                </h2>
                <button onclick="closeAddEventModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
        
            <form id="add-event-form" class="space-y-4" data-mode="add">
                <input type="hidden" id="event-id">
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
                    <!-- Fixed ID here -->
                    <input type="text" id="event-location" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Image Link/Filename</label>
                    <input type="text" id="event-img" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div> <!-- Fixed missing closing div here! -->

                <div class="flex justify-end space-x-3 mt-6">
                    <!-- Updated button styling to match manage_guides -->
                    <button type="button" onclick="closeAddEventModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300 transition">Cancel</button>
                    <button type="submit" id="submit-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">Save Event</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Removed the container wrapper and gray background block to make it full width and subtle -->
    <footer class="w-full text-center py-6 mt-auto text-sm text-gray-500">
        &copy; 2026 RENTramuros. All rights reserved.
    </footer>

    <script src="asset/js/events_management.js"></script>
</body>
</html>