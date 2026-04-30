<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Queuing System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./asset/css/admin.css"> </head>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4 py-6 flex-grow">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold"><i class="fas fa-users mr-3"></i>RENTramuros</h1>
                <div class="text-right">
                    <div id="current-time" class="text-xl font-mono"></div>
                    <div class="text-sm">Welcome, Admin</div>
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
                        <i class="fa-solid fa-horse text-2x1"></i>
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
                    <h2 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-plus-circle mr-2"></i>Add New Tour Guide</h2>
                    
                    <form id="add-guide-form" class="space-y-4">
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

                        <button type="submit"
                                class="w-full bg-gray-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-gray-700 transition">
                            <i class="fas fa-user-plus mr-2"></i>Create Guide Account
                        </button>
                    </form>
                </div>
            </div>

            <!-- Queue Management -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6 card-hover">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-list mr-2"></i>Tour Guide Management</h2>
                        <div class="flex space-x-2">
                            <button class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                                <i class="fas fa-sync-alt mr-2"></i>Refresh
                            </button>
                        </div>
                    </div>

                 <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">On Tour</h3>
                        <div id="onTourCards" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            <div class="border rounded-lg p-4 bg-green-50 border-green-200 shadow-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-bold text-black-500">#58</span>
                                    <span class="px-2 py-1 rounded text-xs bg-green-200 text-green-800 font-medium">On Tour</span>
                                </div>
                                <h4 class="font-semibold text-gray-800 text-lg mb-1">David Lloyd Contreras</h4>
                                <div class="text-gray-600 mb-4">Touring: Princess Rola Motus</div>
                                <button class="font-bold text-black hover:underline text-sm">View Details</button>
                            </div>

                            <div class="border rounded-lg p-4 bg-green-50 border-green-200 shadow-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-bold text-black-500">#59</span>
                                    <span class="px-2 py-1 rounded text-xs bg-green-200 text-green-800 font-medium">On Tour</span>
                                </div>
                                <h4 class="font-semibold text-gray-800 text-lg mb-1">Nesto Arevalo</h4>
                                <div class="text-gray-600 mb-4">Touring: Araw David</div>
                                <button class="font-bold text-black hover:underline text-sm">View Details</button>
                            </div>

                            <div class="border rounded-lg p-4 bg-green-50 border-green-200 shadow-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-bold text-black-500">#60</span>
                                    <span class="px-2 py-1 rounded text-xs bg-green-200 text-green-800 font-medium">On Tour</span>
                                </div>
                                <h4 class="font-semibold text-gray-800 text-lg mb-1">Lencey Bou</h4>
                                <div class="text-gray-600 mb-4">Touring: Kenshin Silawet</div>
                                <button class="font-bold text-black hover:underline text-sm">View Details</button>
                            </div>

                        </div>
                    </div>

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
                            </tbody>
                        </table>
                    </div>
                </div>
             </div>
        </div>
    </div>
        <div id="edit-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex justify-center items-center">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-edit mr-2"></i>Edit Tour Guide</h2>
            
            <form id="edit-guide-form" class="space-y-4">
                <input type="hidden" id="edit-guide-id">
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" id="edit-fname" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" id="edit-lname" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="edit-email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password (Optional)</label>
                    <input type="password" id="edit-password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Leave blank to keep current password">
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-400 text-white py-6 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; RENTramuros. All rights reserved.</p>
        </div>
    </footer>

    <script src="asset/js/dashboard_stats.js"></script>
    <script src="asset/js/guide_management.js"></script>
</body>
</html>