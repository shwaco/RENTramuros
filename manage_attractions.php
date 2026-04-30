<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Attractions - RENTramuros</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="asset/css/admin.css">
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- New Burgundy Header -->
    <header class="bg-[#7a3229] text-white py-4 shadow-md w-full">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <button class="text-white hover:text-gray-300 focus:outline-none transition"><i class="fas fa-bars text-xl"></i></button>
                <div class="flex items-center space-x-2 text-xl tracking-wide cursor-pointer">
                    <i class="far fa-image text-2xl"></i>
                    <span class="font-bold">RENT<span class="font-light">ramuros</span></span>
                </div>
            </div>
            <nav class="flex items-center space-x-8 text-sm font-medium">
                <a href="admin.php" class="hover:text-gray-300 transition">Dashboard</a>
                <a href="manage_guides.php" class="hover:text-gray-300 transition">Guides</a>
                <a href="manage_events.php" class="hover:text-gray-300 transition">Events</a>
                <div class="w-8 h-8 bg-gray-200 rounded-full cursor-pointer hover:bg-gray-300 transition flex items-center justify-center text-gray-800 font-bold"><i class="fas fa-user"></i></div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 flex-grow">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-archway mr-2 text-[#7a3229]"></i>Active Attractions
                </h2>
                <button onclick="openAttractionModal()" class="bg-[#7a3229] text-white px-5 py-2 rounded-lg font-semibold hover:bg-red-900 transition flex items-center">
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
                            <option value="Museum">Museum</option>
                            <option value="Historical Site">Historical Site</option>
                            <option value="Church">Church</option>
                            <option value="Park">Park</option>
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

    <script src="asset/js/attractions_management.js"></script>
</body>
</html>