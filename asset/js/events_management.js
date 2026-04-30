// js/events_management.js

const EVENTS_API_URL = 'http://localhost/RENTramuros/backend.v2/upcoming_events/get_upcoming_events.php'; // Adjust path!

async function loadEvents() {
    try {
        const response = await fetch(EVENTS_API_URL);
        const json = await response.json();

        if (json.status === "success") {
            const events = json.data;
            const tableBody = document.getElementById('events-table-body');
            tableBody.innerHTML = ''; 

            events.forEach(event => {
                const rowHTML = `
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-3 text-sm font-bold text-gray-800">${event.event_id}</td>
                        <td class="px-3 py-3 text-sm text-gray-600 font-semibold">${event.event_name}</td>
                        <td class="px-3 py-3 text-xs text-gray-500 font-mono">${event.event_date}</td>
                        <td class="px-3 py-3 text-sm text-gray-600 truncate max-w-xs">${event.event_time}</td>
                        <td class="px-3 py-3 text-sm text-gray-600 truncate max-w-xs">${event.location}</td>
                        <td class="px-3 py-3 text-sm text-gray-600 truncate max-w-xs">${event.image_file}</td>
                        <td class="px-3 py-3 text-center">
                            <button onclick="openEventEditModal(${event.event_id})" class="bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 px-2 py-1 rounded text-xs font-bold mr-1"><i class="fas fa-edit mr-1"></i>Edit</button>
                            <button onclick="deleteEvent(${event.event_id})" class="bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 px-2 py-1 rounded text-xs font-bold"><i class="fas fa-trash-alt mr-1"></i>Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', rowHTML);
            });
        } else {
            console.error("API Error:", json.message);
        }
    } catch (error) {
        console.error("Failed to fetch events:", error);
    }
}

// Load events when the page loads
document.addEventListener('DOMContentLoaded', loadEvents);

const DELETE_EVENT_API = 'backend.v2/delete_upcoming_events.php';

async function deleteEvent(eventId) {
    if (!confirm("Are you sure you want to delete this event? This cannot be undone.")) {
        return;
    }

    try {
        const response = await fetch(DELETE_EVENT_API, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ event_id: eventId })
        });

        const json = await response.json();

        if (json.status === "success") {
            alert("Event deleted!");
            loadEvents(); // Refresh the table
        } else {
            alert("Error: " + json.message);
        }
    } catch (error) {
        console.error("Delete failed:", error);
    }
}

function openEventEditModal(id, name, desc, schedule, img) {
    document.getElementById('edit-event-id').value = id;
    document.getElementById('edit-event-name').value = name;
    document.getElementById('edit-event-desc').value = desc;
    document.getElementById('edit-event-schedule').value = schedule;
    document.getElementById('edit-event-img').value = img;
    
    document.getElementById('edit-event-modal').classList.remove('hidden');
}

const PATCH_EVENT_API = 'backend.v2/patch_upcoming_events.php';

document.getElementById('edit-event-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const payload = {
        event_id: document.getElementById('edit-event-id').value,
        event_name: document.getElementById('edit-event-name').value,
        description: document.getElementById('edit-event-desc').value,
        schedule: document.getElementById('edit-event-schedule').value,
        main_img: document.getElementById('edit-event-img').value
    };

    try {
        const response = await fetch(PATCH_EVENT_API, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const json = await response.json();

        if (json.status === "success") {
            alert("Update successful!");
            document.getElementById('edit-event-modal').classList.add('hidden');
            loadEvents();
        } else {
            alert("Update failed: " + json.message);
        }
    } catch (error) {
        console.error("Patch Error:", error);
    }
});

 function openAddEventModal() { document.getElementById('add-event-modal').classList.remove('hidden'); }
        function closeAddEventModal() { 
            document.getElementById('add-event-modal').classList.add('hidden'); 
            document.getElementById('add-event-form').reset();
        }