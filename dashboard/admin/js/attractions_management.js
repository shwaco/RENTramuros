// asset/js/attractions_management.js

// Adjust these paths to your actual PHP files!
const GET_API = 'backend.v2/attractions/get_attractions.php';
const POST_API = 'backend.v2/attractions/post_attractions.php';
const PATCH_API = 'backend.v2/attractions/patch_attractions.php';
const DELETE_API = 'backend.v2/attractions/delete_attractions.php';

// ==========================================
// 1. LOAD DATA
// ==========================================
async function loadAttractions() {
    try {
        const response = await fetch(GET_API);
        const json = await response.json();

        if (json.status === "success") {
            const tbody = document.getElementById('attractions-table-body');
            tbody.innerHTML = ''; 

            json.data.forEach(attr => {
                // Formatting the fee to ensure it shows 2 decimal places
                const formattedFee = parseFloat(attr.fee).toFixed(2);

                const row = `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-bold text-gray-800">${attr.attraction_id}</td>
                        <td class="px-4 py-3 text-xs text-gray-500 font-mono">${attr.attraction_type}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-[#7a3229]">${attr.attraction_name}</td>
                        <td class="px-4 py-3 text-xs text-gray-500 font-mono">${attr.address}</td>
                        <td class="px-4 py-3 text-xs text-gray-500 font-mono">${attr.description}</td>
                        <td class="px-4 py-3 text-xs text-gray-500 font-mono">${attr.schedule}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 font-bold">₱${formattedFee}</td>
                        <td class="px-4 py-3 text-xs text-gray-500 font-mono">${attr.main_img}</td>
                        <td class="px-4 py-3 text-xs text-gray-500 font-mono">${attr.mini_one_img}</td>
                        <td class="px-4 py-3 text-xs text-gray-500 font-mono">${attr.mini_two_img}</td>
                        <td class="px-4 py-3 text-xs text-gray-500 font-mono">${attr.rec_img}</td>
                        <td class="px-4 py-3 text-center">            
                            <button onclick="openEditModal('${attr.attraction_id}', '${attr.attraction_name}', '${attr.attraction_type}', '${attr.fee}', '${attr.address}', '${attr.schedule}', '${attr.description}', '${attr.main_img}', '${attr.rec_img}', '${attr.mini_one_img}', '${attr.mini_two_img}')" class="bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 px-2 py-1 rounded text-xs font-bold mr-1">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteAttraction(${attr.attraction_id})" class="bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 px-2 py-1 rounded text-xs font-bold">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }
    } catch (error) {
        console.error("Failed to fetch attractions:", error);
    }
}

document.addEventListener('DOMContentLoaded', loadAttractions);

// ==========================================
// 2. MODAL CONTROLS (Add vs Edit)
// ==========================================
function openAttractionModal() {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-plus-circle mr-2"></i>Add Attraction';
    document.getElementById('submit-btn').innerText = "Save Attraction";
    document.getElementById('attraction-form').dataset.mode = "add";
    document.getElementById('attraction-modal').classList.remove('hidden');
}

function openEditModal(id, name, type, fee, address, schedule, desc, mainImg, recImg, mini1, mini2) {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-edit mr-2"></i>Update Attraction';
    document.getElementById('submit-btn').innerText = "Save Changes";
    document.getElementById('attraction-form').dataset.mode = "edit";
    
    // Fill all the inputs
    document.getElementById('attr-id').value = id;
    document.getElementById('attr-name').value = name;
    document.getElementById('attr-type').value = type;
    document.getElementById('attr-fee').value = fee;
    document.getElementById('attr-address').value = address;
    document.getElementById('attr-schedule').value = schedule;
    document.getElementById('attr-desc').value = desc !== 'undefined' ? desc : '';
    document.getElementById('attr-img-main').value = mainImg !== 'undefined' ? mainImg : '';
    document.getElementById('attr-img-rec').value = recImg !== 'undefined' ? recImg : '';
    document.getElementById('attr-img-mini1').value = mini1 !== 'undefined' ? mini1 : '';
    document.getElementById('attr-img-mini2').value = mini2 !== 'undefined' ? mini2 : '';

    document.getElementById('attraction-modal').classList.remove('hidden');
}

function closeAttractionModal() {
    document.getElementById('attraction-modal').classList.add('hidden');
    document.getElementById('attraction-form').reset();
}

// ==========================================
// 3. SUBMIT FORM (Post & Patch)
// ==========================================
document.getElementById('attraction-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const mode = this.dataset.mode;
    const isEdit = mode === "edit";
    const API_URL = isEdit ? PATCH_API : POST_API;
    const METHOD = isEdit ? 'PATCH' : 'POST';

    // Inside the submit listener, replace the old payload object with this:
    const payload = {
        attraction_name: document.getElementById('attr-name').value,
        attraction_type: document.getElementById('attr-type').value,
        fee: document.getElementById('attr-fee').value,
        address: document.getElementById('attr-address').value,
        schedule: document.getElementById('attr-schedule').value,
        description: document.getElementById('attr-desc').value,
        main_img: document.getElementById('attr-img-main').value,
        rec_img: document.getElementById('attr-img-rec').value,
        mini_one_img: document.getElementById('attr-img-mini1').value,
        mini_two_img: document.getElementById('attr-img-mini2').value
    };

    if (isEdit) payload.attraction_id = document.getElementById('attr-id').value;

    try {
        const response = await fetch(API_URL, {
            method: METHOD,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const json = await response.json();

        if (json.status === "success") {
            alert(isEdit ? "Attraction updated!" : "Attraction created!");
            closeAttractionModal();
            loadAttractions();
        } else {
            alert("Error: " + json.message);
        }
    } catch (error) {
        console.error("Save failed:", error);
    }
});

// ==========================================
// 4. DELETE
// ==========================================
async function deleteAttraction(id) {
    if (!confirm("Delete this attraction? This cannot be undone.")) return;

    try {
        const response = await fetch(DELETE_API, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ attraction_id: id })
        });
        const json = await response.json();

        if (json.status === "success") {
            loadAttractions();
        } else {
            alert("Error: " + json.message);
        }
    } catch (error) {
        console.error("Delete failed:", error);
    }
}