async function loadBookingHistory() {
    try {
        // Pointing to your newly renamed endpoint!
        const response = await fetch('../../backend/api/actions/get_booking_history.php');
        const json = await response.json();

        if (json.status === "success") {
            const tbody = document.getElementById('dashboard-table-body');
            tbody.innerHTML = ''; 

            // Save globally so the dropdown filter works instantly without re-fetching
            window.allBookingsData = json.data; 
            renderBookingsTable(window.allBookingsData);
        }
    } catch (error) {
        console.error("Failed to load booking history:", error);
    }
}

function renderBookingsTable(data) {
    const tbody = document.getElementById('dashboard-table-body');
    tbody.innerHTML = '';

    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="14" class="text-center py-6 text-sm text-gray-500">No bookings found.</td></tr>`;
        return;
    }

    data.forEach(booking => {
        // 1. Handle Status Badges
        let statusBadge = '';
        const status = booking.status ? booking.status.toLowerCase() : '';
        
        if (status === 'pending') statusBadge = 'bg-yellow-100 text-yellow-800';
        else if (status === 'accepted') statusBadge = 'bg-blue-100 text-blue-800';
        else if (status === 'on tour' || status === 'ongoing') statusBadge = 'bg-purple-100 text-purple-800';
        else if (status === 'completed') statusBadge = 'bg-green-100 text-green-800';
        else statusBadge = 'bg-gray-100 text-gray-800';

        // 2. Format names safely (Handling NULLs if data is missing)
        const touristName = `${booking.customer_fname || ''} ${booking.customer_lname || ''}`.trim();
        const guideName = booking.guide_fname ? `${booking.guide_fname} ${booking.guide_lname}` : '<span class="text-gray-400 italic">None</span>';

        // 3. Build the 14-Column Row
        const row = `
            <tr class="hover:bg-gray-50 transition text-xs" data-type="${booking.booking_type ? booking.booking_type.toLowerCase() : ''}">
                
                <td class="px-3 py-3 font-mono font-bold text-[#7a3229]">${booking.unique_id || 'N/A'}</td>
                <td class="px-3 py-3 font-semibold uppercase">${booking.booking_type || 'N/A'}</td>
                
                <td class="px-3 py-3 text-center">${booking.adults_and_seniors || '0'}</td>
                <td class="px-3 py-3 text-center">${booking.children || '0'}</td>
                <td class="px-3 py-3 text-center">${booking.infants || '0'}</td>
                
                <td class="px-3 py-3 font-medium text-gray-800">${touristName || 'N/A'}</td>
                <td class="px-3 py-3 text-blue-600">${booking.customer_email || 'N/A'}</td>
                <td class="px-3 py-3">${booking.customer_phone || 'N/A'}</td>
                
                <td class="px-3 py-3">
                    <div class="font-medium text-gray-800">${booking.booking_date || 'N/A'}</div>
                    <div class="text-gray-500 mt-0.5">${booking.booking_time || ''}</div>
                </td>
                <td class="px-3 py-3 text-center">
                    <span class="${statusBadge} font-bold px-2 py-1 rounded-full uppercase tracking-wide">
                        ${booking.status || 'Unknown'}
                    </span>
                </td>
                
                <td class="px-3 py-3">${booking.vehicle_type || '<span class="text-gray-400 italic">None</span>'}</td>
                <td class="px-3 py-3 text-center">${booking.passenger_capacity || '-'}</td>
                <td class="px-3 py-3">${guideName}</td>
                <td class="px-3 py-3">${booking.guide_email || '-'}</td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

// Dropdown Filter Function
function filterBookings(type) {
    if (!window.allBookingsData) return; 
    
    if (type === 'all') {
        renderBookingsTable(window.allBookingsData);
    } else {
        const filtered = window.allBookingsData.filter(b => 
            b.booking_type && b.booking_type.toLowerCase().includes(type)
        );
        renderBookingsTable(filtered);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', loadBookingHistory);