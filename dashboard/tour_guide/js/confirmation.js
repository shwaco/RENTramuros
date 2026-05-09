// dynamic confirmation modal — ginagamit para sa accept tour, clock out
// binubuksan yung dynamic modal yung for confirming ng guide actions like accepting a tour or clocking out

function openDynamicModal(title, message, confirmCallback, buttonColor = '#16a34a') {
    console.log("Modal was opened...");

    const overlay = document.getElementById('dynamic-confirm-overlay');
    const titleEl = document.getElementById('dynamic-modal-title');
    const msgEl = document.getElementById('dynamic-modal-msg');
    const confirmBtn = document.getElementById('dynamic-modal-btn');

    if (!overlay || !titleEl || !msgEl || !confirmBtn) {
        return console.error("Error: Error cant find mondals.php!");
    }

    titleEl.innerText = title;
    msgEl.innerText = message;
    confirmBtn.style.backgroundColor = buttonColor;

    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

    newConfirmBtn.onclick = function () {
        console.log("Confirmed!");
        closeDynamicModal();
        confirmCallback();
    };

    overlay.style.display = 'flex';
}

function closeDynamicModal() {
    const overlay = document.getElementById('dynamic-confirm-overlay');
    if (overlay) overlay.style.display = 'none';
}