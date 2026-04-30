const API = {
    GET:    'http://localhost/ADMIN_DASHBOARD3/backend.v2/tour_guide/get_guide.php',
    POST:   'http://localhost/ADMIN_DASHBOARD3/backend.v2/tour_guide/post_guide.php',
    PATCH:  'http://localhost/ADMIN_DASHBOARD3/backend.v2/tour_guide/patch_guide.php',
    DELETE: 'http://localhost/ADMIN_DASHBOARD3/backend.v2/tour_guide/delete_guide.php',
};

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const icon  = document.getElementById('toast-icon');
    const msg   = document.getElementById('toast-msg');

    toast.className = `toast ${type}`;
    icon.className  = `toast-icon fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}`;
    msg.textContent = message;

    toast.classList.remove('hidden');
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => toast.classList.add('hidden'), 3500);
}

function showFeedback(elId, message, type = 'success') {
    const el = document.getElementById(elId);
    el.className = `text-sm px-3 py-2 rounded-lg ${type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200'}`;
    el.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-times-circle'} mr-1"></i>${message}`;
    el.classList.remove('hidden');
    clearTimeout(el._timer);
    el._timer = setTimeout(() => el.classList.add('hidden'), 4000);
}

function statusBadge(status) {
    const map = {
        'Online':     ['badge-online',  'Online'],
        'Clocked In': ['badge-primary', 'Clocked In'], 
        'queuing':    ['badge-queuing', 'Queuing'],    
        'On Tour':    ['badge-ontour',  'On Tour'],   
        'Offline':    ['badge-offline', 'Offline']
    };
    const [cls, label] = map[status] || ['badge-offline', status || 'Unknown'];
    return `<span class="${cls}">${label}</span>`;
}

function fmtDate(raw) {
    if (!raw || raw === 'NULL' || raw === 'N/A') return '<span class="text-gray-300">—</span>';
    const d = new Date(raw.replace(' ', 'T'));
    if (isNaN(d)) return raw;
    return d.toLocaleString('en-PH', {
        month: 'short', day: 'numeric',
        hour: 'numeric', minute: '2-digit', hour12: true
    });
}

function renderOnTourCards(guides) {
    const container = document.getElementById('onTourCards');
    const onTour = guides.filter(g =>
        g.current_tourist_id ||
        (g.current_status && g.current_status.toLowerCase().replace(/\s/g,'') === 'On Tour')
    );

    if (onTour.length === 0) {
        container.innerHTML = `<p class="text-sm text-gray-400 italic col-span-3">No guides currently on tour.</p>`;
        return;
    }

    container.innerHTML = onTour.map(g => `
        <div class="border rounded-lg p-4 bg-green-50 border-green-200 shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-bold text-gray-500">#${g.guide_id}</span>
                <span class="px-2 py-1 rounded text-xs bg-green-200 text-green-800 font-semibold">On Tour</span>
            </div>
            <h4 class="font-semibold text-gray-800 mb-1">${escHtml(g.first_name)} ${escHtml(g.last_name)}</h4>
            <p class="text-xs text-gray-500">
                ${g.current_tourist_id ? `Tourist #${g.current_tourist_id}` : 'Tourist assigned'}
            </p>
        </div>
    `).join('');
}

async function loadTourGuides() {
    const tbody = document.getElementById('guide-table-body');
    tbody.innerHTML = `
        <tr>
            <td colspan="10" class="px-3 py-6 text-center text-gray-400 italic">
                <i class="fas fa-spinner fa-spin mr-2"></i>Loading guides...
            </td>
        </tr>`;

    try {
        const res  = await fetch(API.GET);
        const json = await res.json();

        if (json.status !== 'success') {
            tbody.innerHTML = `<tr><td colspan="10" class="px-3 py-6 text-center text-red-400">⚠ ${json.message || 'Failed to load guides.'}</td></tr>`;
            return;
        }

        const guides = json.data;
        renderOnTourCards(guides);

        if (guides.length === 0) {
            tbody.innerHTML = `<tr><td colspan="10" class="px-3 py-6 text-center text-gray-400 italic">No guides found.</td></tr>`;
            return;
        }

        tbody.innerHTML = guides.map(g => `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-3 py-3 text-sm font-bold text-gray-700">${g.guide_id}</td>
                <td class="px-3 py-3 text-sm text-gray-800">${escHtml(g.first_name)}</td>
                <td class="px-3 py-3 text-sm text-gray-800">${escHtml(g.last_name)}</td>
                <td class="px-3 py-3 text-xs text-gray-500 max-w-email truncate" title="${escHtml(g.email)}">${escHtml(g.email)}</td>
                <td class="px-3 py-3">${statusBadge(g.current_status)}</td>
                <td class="px-3 py-3 text-xs text-gray-500">${fmtDate(g.last_active_at)}</td>
                <td class="px-3 py-3 text-xs text-gray-500">${fmtDate(g.last_dispatch_time)}</td>
                <td class="px-3 py-3 text-xs text-gray-500">${fmtDate(g.became_available_at)}</td>
                <td class="px-3 py-3 text-center text-xs ${g.current_tourist_id ? 'text-gray-700 font-semibold' : 'text-gray-300 italic'}">
                    ${g.current_tourist_id || 'NULL'}
                </td>
                <td class="px-3 py-3">
                    <div class="flex gap-1 justify-center">
                        <button onclick="openEditModal(${g.guide_id}, '${escAttr(g.first_name)}', '${escAttr(g.last_name)}', '${escAttr(g.email)}')"
                                class="bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 px-2 py-1 rounded text-xs font-semibold transition">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                        <button onclick="deleteGuide(${g.guide_id})"
                                class="bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 px-2 py-1 rounded text-xs font-semibold transition">
                            <i class="fas fa-trash-alt mr-1"></i>Del
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

    } catch (err) {
        console.error('Failed to fetch guides:', err);
        tbody.innerHTML = `<tr><td colspan="10" class="px-3 py-6 text-center text-red-400">Network error — could not load guides.</td></tr>`;
    }
}

document.getElementById('add-guide-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const btn = document.getElementById('add-guide-btn');
    btn.disabled = true;
    btn.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i>Saving...`;

    const payload = {
        first_name: document.getElementById('guide-fname').value.trim(),
        last_name:  document.getElementById('guide-lname').value.trim(),
        email:      document.getElementById('guide-email').value.trim(),
        password:   document.getElementById('guide-password').value,
    };

    try {
        const res  = await fetch(API.POST, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(payload),
        });
        const json = await res.json();

        if (json.status === 'success') {
            showFeedback('add-guide-feedback', 'Tour guide added successfully!', 'success');
            showToast('Tour guide created!', 'success');
            this.reset();
            loadTourGuides();
        } else {
            showFeedback('add-guide-feedback', json.message || 'Failed to add guide.', 'error');
        }
    } catch (err) {
        console.error('Network error:', err);
        showFeedback('add-guide-feedback', 'Network error — please try again.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<i class="fas fa-user-plus mr-2"></i>Create Guide Account`;
    }
});

// ── 2. EDIT GUIDE (PATCH) ─────────────────────────────────────
document.getElementById('edit-guide-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const btn = document.getElementById('edit-guide-btn');
    btn.disabled = true;
    btn.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i>Saving...`;

    const payload = {
        guide_id:   document.getElementById('edit-guide-id').value,
        first_name: document.getElementById('edit-fname').value.trim(),
        last_name:  document.getElementById('edit-lname').value.trim(),
        email:      document.getElementById('edit-email').value.trim(),
    };

    const newPass = document.getElementById('edit-password').value;
    if (newPass.trim() !== '') payload.password = newPass;

    try {
        const res  = await fetch(API.PATCH, {
            method:  'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(payload),
        });
        const json = await res.json();

        if (json.status === 'success') {
            showToast('Guide updated successfully!', 'success');
            closeEditModal();
            loadTourGuides();
        } else {
            showFeedback('edit-guide-feedback', json.message || 'Update failed.', 'error');
        }
    } catch (err) {
        console.error('Failed to update guide:', err);
        showFeedback('edit-guide-feedback', 'Network error — please try again.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<i class="fas fa-save mr-1"></i>Save Changes`;
    }
});

// ── 3. DELETE GUIDE ───────────────────────────────────────────
async function deleteGuide(guideId) {
    if (!confirm('Are you sure you want to delete this tour guide? This cannot be undone.')) return;

    try {
        const res  = await fetch(API.DELETE, {
            method:  'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ guide_id: guideId }),
        });
        const json = await res.json();

        if (json.status === 'success') {
            showToast('Tour guide removed.', 'success');
            loadTourGuides();
        } else {
            showToast(json.message || 'Delete failed.', 'error');
        }
    } catch (err) {
        console.error('Failed to delete guide:', err);
        showToast('Network error while deleting.', 'error');
    }
}

// ── 4. MODAL CONTROLS ─────────────────────────────────────────
function openEditModal(id, fname, lname, email) {
    document.getElementById('edit-guide-id').value  = id;
    document.getElementById('edit-fname').value     = fname;
    document.getElementById('edit-lname').value     = lname;
    document.getElementById('edit-email').value     = email;
    document.getElementById('edit-password').value  = '';
    document.getElementById('edit-guide-feedback').className = 'hidden';
    document.getElementById('edit-modal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}

// Close modal on overlay click
document.getElementById('edit-modal').addEventListener('click', function (e) {
    if (e.target === this) closeEditModal();
});

// ── Helpers ───────────────────────────────────────────────────
function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function escAttr(str) {
    return String(str).replace(/'/g, "\\'").replace(/"/g, '&quot;');
}



// ── Init ──────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', loadTourGuides);