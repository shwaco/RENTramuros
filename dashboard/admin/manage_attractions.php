<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Attractions - RENTramuros</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- New Burgundy Header -->
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
                            Home Page
                        </a>
                        <a href="manage_guides.php" class="text-gray-300 font-medium px-1 py-2 hover:text-white transition duration-150">
                            Guides
                        </a>
                        <!-- Active Link (Events) -->
                        <a href="manage_events.php" class="text-gray-300 font-medium px-1 py-2 hover:text-white transition duration-150">
                            Events
                        </a>
                        <a href="manage_attractions.php" class="text-gray-300 font-medium px-1 py-2 hover:text-white transition duration-150">
                            Attractions
                        </a>
                         <a href="statistics_overview.php" class="text-gray-300 font-medium px-1 py-2 hover:text-white transition duration-150">
                            Statistics
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

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 flex-grow">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-archway mr-2 text-[#7a3229]"></i>Attractions
                </h2>
                <button onclick="openAttractionModal()"  class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i>Add Attraction
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-600 uppercase text-xs tracking-wider">
                            <th class="px-4 py-3 font-bold border-b">ID</th>
                            <th class="px-4 py-3 font-bold border-b">Type</th>
                            <th class="px-4 py-3 font-bold border-b">Name</th>
                            <th class="px-4 py-3 font-bold border-b">Address</th>
                            <th class="px-4 py-3 font-bold border-b">Description</th>
                            <th class="px-4 py-3 font-bold border-b">Schedule</th>
                            <th class="px-4 py-3 font-bold border-b">Fee (₱)</th>
                            <th class="px-4 py-3 font-bold border-b">Main Image</th>
                            <th class="px-4 py-3 font-bold border-b">Mini Image One</th>
                            <th class="px-4 py-3 font-bold border-b">Mini Image Two</th>
                            <th class="px-4 py-3 font-bold border-b">Rectangular Image</th>
                            <th class="px-4 py-3 font-bold border-b text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="attractions-table-body" class="text-gray-700 divide-y divide-gray-100">
                        <!-- JavaScript will populate this -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Unified Add/Edit Modal -->
    <!-- Unified Add/Edit Modal (Expanded) -->
    <div id="attraction-modal" class="hidden fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <!-- Increased width to max-w-2xl to comfortably fit the new fields -->
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto relative">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modal-title" class="text-xl font-bold text-gray-800"><i class="fas fa-edit mr-2"></i>Manage Attraction</h2>
                <button onclick="closeAttractionModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
        
            <form id="attraction-form" class="space-y-4" data-mode="add">
                <input type="hidden" id="attr-id">
                
                <!-- Basic Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b pb-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Attraction Name</label>
                        <input type="text" id="attr-name" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#7a3229] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Type</label>
                        <select id="attr-type" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#7a3229] outline-none bg-white">
                            <option value="Recommended">Recommended</option>
                            <option value="Popular">Popular</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Entrance Fee (₱)</label>
                        <input type="number" step="0.01" id="attr-fee" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#7a3229] outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                        <input type="text" id="attr-address" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#7a3229] outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Schedule / Hours</label>
                        <input type="text" id="attr-schedule" placeholder="e.g., Mon-Sun 8AM - 5PM" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#7a3229] outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                        <textarea id="attr-desc" rows="3" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#7a3229] outline-none resize-none"></textarea>
                    </div>
                </div>

                <!-- Images Grid -->
                <div>
                    <h3 class="text-sm font-bold text-gray-800 mb-3 uppercase tracking-wide">Image Assets</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Main Image Link</label>
                            <input type="text" id="attr-img-main" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#7a3229] outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Recommendation Image Link</label>
                            <input type="text" id="attr-img-rec" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#7a3229] outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Mini Image 1 Link</label>
                            <input type="text" id="attr-img-mini1" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#7a3229] outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Mini Image 2 Link</label>
                            <input type="text" id="attr-img-mini2" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#7a3229] outline-none text-sm">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeAttractionModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300 transition">Cancel</button>
                    <button type="submit" id="submit-btn" class="px-4 py-2 bg-[#7a3229] text-white rounded-lg font-semibold hover:bg-red-900 transition">Save Attraction</button>
                </div>
            </form>
        </div>
    </div>

    <!-- New Burgundy Footer -->
    <footer class="bg-[#7a3229] text-white pt-12 pb-6 mt-auto w-full">
        <!-- (Paste the exact footer code I gave you in the last prompt here!) -->
        <div class="container mx-auto px-6 text-center text-sm">
            <p>All right reserved. Copyright &copy; 2026 RENTramuros Manila.</p>
        </div>
    </footer>

    <script src="js/attractions_management.js"></script>
</body>
</html>