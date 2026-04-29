const GUIDE_API_URL = 'http://localhost/RENTramuros/backend.v2/tour_guide/get_guide.php'; 

async function loadTourGuides() {
    try {
        const response = await fetch(GUIDE_API_URL);
        const json = await response.json();

        if (json.status === "success") {
            const guides = json.data;
            const tableBody = document.getElementById('guide-table-body');
            tableBody.innerHTML = ''; 

            guides.forEach(guide => {
                let statusBadge = '';
                if (guide.current_status === 'Online') {
                    statusBadge = `<span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 font-medium">Online</span>`;
                } else if (guide.current_status === 'Queuing') {
                    statusBadge = `<span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 font-medium">Queuing</span>`;
                } else {
                    statusBadge = `<span class="px-2 py-1 rounded-full bg-gray-100 text-gray-600 font-medium">Offline</span>`;
                }

                const rowHTML = `
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-3 text-sm font-bold text-gray-800">${guide.guide_id}</td>
                        <td class="px-3 py-3 text-sm text-gray-600">${guide.first_name}</td>
                        <td class="px-3 py-3 text-sm text-gray-600">${guide.last_name}</td>
                        <td class="px-3 py-3 text-xs text-gray-600">${guide.email}</td>
                        <td class="px-3 py-3 text-xs">${statusBadge}</td>
                        <td class="px-3 py-3 text-xs text-gray-500 font-mono">${guide.last_active_at || 'N/A'}</td>
                        <td class="px-3 py-3 text-xs text-gray-500 font-mono">${guide.last_dispatch_time || 'N/A'}</td>
                        <td class="px-3 py-3 text-xs text-gray-500 font-mono">${guide.became_available_at || 'N/A'}</td>
                        <td class="px-3 py-3 text-sm text-center text-gray-400 italic">${guide.current_tourist_id || 'NULL'}</td>
                        <td class="px-3 py-3 text-center">  
                            <button onclick="openEditModal(${guide.guide_id}, '${guide.first_name}', '${guide.last_name}', '${guide.email}')" 
                                    class="bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 px-2 py-1 rounded text-xs font-bold mr-1 transition">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            <button onclick="deleteGuide(${guide.guide_id})" class="bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 px-2 py-1 rounded text-xs font-bold transition">
                                <i class="fas fa-trash-alt mr-1"></i>Delete
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', rowHTML);
            });
        } else {
            console.error("API Error:", json.message);
        }
    } catch (error) {
        console.error("Failed to fetch guides:", error);
    }
}

document.addEventListener('DOMContentLoaded', loadTourGuides);

// Add this below your loadTourGuides() function

// Point this to your newly organized API path!
// ==========================================
// 1. ADD NEW TOUR GUIDE LOGIC (POST)
// ==========================================
const POST_GUIDE_API = 'http://localhost/RENTramuros/backend.v2/tour_guide/post_guide.php';

document.getElementById('add-guide-form').addEventListener('submit', async function(e) {
    e.preventDefault(); 

    // Grab the inputs from the main dashboard form
    const payload = {
        first_name: document.getElementById('guide-fname').value,
        last_name: document.getElementById('guide-lname').value,
        email: document.getElementById('guide-email').value,
        password: document.getElementById('guide-password').value
    };

    try {
        const response = await fetch(POST_GUIDE_API, {
            method: 'POST', // Strictly POST for creating
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const json = await response.json();

        if (json.status === "success") {
            alert("Success: Tour Guide added to the system!");
            document.getElementById('add-guide-form').reset(); 
            loadTourGuides(); 
        } else {
            alert("Error adding guide: " + json.message);
        }
    } catch (error) {
        console.error("Network Error:", error);
        alert("A network error occurred while trying to save the tour guide.");
    }
});

// ==========================================
// 2. EDIT EXISTING TOUR GUIDE LOGIC (PATCH)
// ==========================================
const PATCH_GUIDE_API = 'http://localhost/RENTramuros/backend.v2/tour_guide/patch_guide.php';

document.getElementById('edit-guide-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    // Grab the inputs from the hidden edit modal
    const payload = {
        guide_id: document.getElementById('edit-guide-id').value,
        first_name: document.getElementById('edit-fname').value,
        last_name: document.getElementById('edit-lname').value,
        email: document.getElementById('edit-email').value
    };

    // Grab the password box
    const newPassword = document.getElementById('edit-password').value;

    // Only add the password to the JSON if they actually typed something!
    if (newPassword.trim() !== "") {
        payload.password = newPassword;
    }

    try {
        const response = await fetch(PATCH_GUIDE_API, {
            method: 'PATCH', // Strictly PATCH for updating
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const json = await response.json();

        if (json.status === "success") {
            alert("Guide updated successfully!");
            closeEditModal();
            loadTourGuides(); 
        } else {
            alert("Error: " + json.message);
        }
    } catch (error) {
        console.error("Failed to update guide:", error);
        alert("A network error occurred.");
    }
});

// ==========================================
// 3. MODAL CONTROLS
// ==========================================

// Function to open the modal and pre-fill the data
function openEditModal(id, fname, lname, email) {
    document.getElementById('edit-guide-id').value = id;
    document.getElementById('edit-fname').value = fname;
    document.getElementById('edit-lname').value = lname;
    document.getElementById('edit-email').value = email;
    
    // Make sure the password box is completely empty when it opens
    document.getElementById('edit-password').value = ''; 
    
    // Un-hide the modal
    document.getElementById('edit-modal').classList.remove('hidden');
}

// Function to close the modal
function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}

// Point this to your actual delete API path!
const DELETE_GUIDE_API = 'http://localhost/RENTramuros/backend.v2/tour_guide/delete_guide.php';

async function deleteGuide(guideId) {
    // 1. Safety first: Ask the admin to confirm before deleting
    const isConfirmed = confirm("Are you absolutely sure you want to delete this Tour Guide? This cannot be undone.");
    
    if (!isConfirmed) {
        return; // Stop the function if they click "Cancel"
    }

    try {
        // 2. Send the DELETE request with the guide_id in the JSON body
        const response = await fetch(DELETE_GUIDE_API, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ guide_id: guideId })
        });

        const json = await response.json();

        // 3. Handle the response
        if (json.status === "success") {
            alert("Tour guide successfully removed.");
            
            // Re-fetch the table data so the deleted row instantly disappears!
            loadTourGuides(); 
        } else {
            alert("Error: " + json.message);
        }
    } catch (error) {
        console.error("Failed to delete guide:", error);
        alert("A network error occurred while trying to delete the guide.");
    }
}