<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Attractions - RENTramuros</title>
    <!-- Upgraded to Tailwind v3 CDN which natively supports your custom [#7a3229] colors! -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="flex h-screen bg-gray-50 antialiased overflow-hidden">

    <!-- HOVERABLE SIDEBAR -->
    <!-- 'relative' keeps it in the flow, 'shrink-0' prevents it from getting squished -->
    <aside class="relative h-screen bg-gray-800 text-white flex flex-col shadow-2xl transition-all duration-300 w-20 hover:w-64 overflow-hidden group hidden md:flex shrink-0 z-50">
        
        <!-- Logo Area -->
        <div class="flex items-center h-20 px-4 border-b border-gray-700 whitespace-nowrap">
            <div class="w-12 flex justify-center shrink-0">
                <i class="fas fa-chess-rook text-3xl text-gray-300"></i>
            </div>
            <div class="ml-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                <div class="font-bold text-xl tracking-wide">RENT<span class="font-light">ramuros</span></div>
                <div class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Admin Hub</div>
            </div>
        </div>

        <!-- Navigation Links -->
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
            
            <!-- ACTIVE TAB (Attractions) -->
            <a href="manage_attractions.php" class="flex items-center py-3 bg-[#7a3229] text-white rounded-lg shadow-md transition whitespace-nowrap">
                <div class="w-12 flex justify-center shrink-0"><i class="fas fa-archway text-xl"></i></div>
                <span class="ml-3 font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300">Attractions</span>
            </a>
            
            <a href="statistics_overview.php" class="flex items-center py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition whitespace-nowrap">
                <div class="w-12 flex justify-center shrink-0"><i class="fas fa-chart-pie text-xl"></i></div>
                <span class="ml-3 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Statistics</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <!-- 'flex-1' allows this container to dynamically resize based on the sidebar's width -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative bg-gray-50">
        
        <!-- Minimal Top Header -->
        <header class="bg-white shadow-sm py-4 px-6 flex justify-between items-center z-10 border-b border-gray-200">
            <button class="md:hidden text-gray-600 focus:outline-none hover:text-[#7a3229]">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <div class="hidden md:block"></div>

            <!-- User Controls -->
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

        <!-- Scrollable Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
            
            <!-- Table Container -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8 border border-gray-100">
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-archway mr-2 text-[#7a3229]"></i>Attractions
                    </h2>
                    <button onclick="openAttractionModal()" class="bg-[#7a3229] text-white px-5 py-2 rounded-lg font-semibold hover:bg-red-900 transition flex items-center shadow-sm">
                        <i class="fas fa-plus-circle mr-2"></i>Add Attraction
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-left text-gray-600 uppercase text-xs tracking-wider border-b border-gray-200">
                                <th class="px-4 py-4 font-bold">ID</th>
                                <th class="px-4 py-4 font-bold">Type</th>
                                <th class="px-4 py-4 font-bold">Name</th>
                                <th class="px-4 py-4 font-bold">Address</th>
                                <th class="px-4 py-4 font-bold">Description</th>
                                <th class="px-4 py-4 font-bold">Schedule</th>
                                <th class="px-4 py-4 font-bold">Fee (₱)</th>
                                <th class="px-4 py-4 font-bold">Main Image</th>
                                <th class="px-4 py-4 font-bold">Mini Image One</th>
                                <th class="px-4 py-4 font-bold">Mini Image Two</th>
                                <th class="px-4 py-4 font-bold">Rectangular Image</th>
                                <th class="px-4 py-4 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="attractions-table-body" class="text-gray-700 divide-y divide-gray-100">
                            <!-- JavaScript will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
          <!-- FOOTER: Outside of <main> but securely inside the Flex Wrapper -->
            <!-- This locks the footer permanently to the bottom of the screen! -->
            <footer class="bg-white border-t border-gray-200 w-full text-center py-4 text-sm text-gray-500 shrink-0">
                &copy; 2026 RENTramuros. All rights reserved.
            </footer>
    </div>

    <!-- Unified Add/Edit Modal -->
    <div id="attraction-modal" class="hidden fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto relative">
            <div class="flex justify-between items-center mb-6 border-b pb-3">
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

    <!-- JavaScript Links -->
    <script src="js/attractions_management.js"></script>
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