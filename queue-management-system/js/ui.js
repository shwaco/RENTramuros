// logout, clock out confirmation, view switching, profile dropdown

async function handleLogoutOnly() {
    try {
        const response = await fetch('api/logout_api.php', { method: 'POST' });
        const result = await response.json();
        // kapag successful yung logout, iriredirect pabalik sa login page
        if (result.status === 'success') {
            window.location.href = "../auth/login.html";
        }
    } catch (error) {
        console.error("Logout Error:", error);
    }
}

function handleClockOut() {
    // inoopen muna yung confirmation modal bago i-execute — para hindi maa-aksidente
    openDynamicModal(
        "Clock Out?",
        "Are you sure you want to clock out? You will lose your current place in the queue!",
        () => executeClockOut(),
        "#dc2626"
    );
}

async function executeClockOut() {
    try {
        const response = await fetch('api/clock_out.php', { method: 'POST' });
        const result = await response.json();
        if (result.success) {
            // kung successful, irereload yung web para bumalik sa Online state ng dashboard
            window.location.reload();
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Clock Out Error:", error);
    }
}

// para sa switching ng dashboard at history views
function switchView(viewName) {
    const dashboard = document.getElementById('active-tour-view');
    const history = document.getElementById('history-view');

    if (viewName === 'dashboard') {
        if (dashboard) dashboard.style.display = 'flex';
        if (history) history.style.display = 'none';
    } else if (viewName === 'history') {
        if (dashboard) dashboard.style.display = 'none';
        if (history) history.style.display = 'flex';
    }
}

// ininitialize yung profile dropdown toggle
function initProfileDropdown() {
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    if (profileBtn && profileMenu) {    
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle('show');
        });

        // kung na-click yung kahit anong lugar sa labas ng dropdown, isasara ito
        document.addEventListener('click', (e) => {
            if (!profileMenu.contains(e.target)) {
                profileMenu.classList.remove('show');
            }
        });
    }
}