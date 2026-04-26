// dynamic confirmation modal — ginagamit para sa accept tour, clock out

function openDynamicModal(title, message, confirmCallback, buttonColor = '#16a34a') {
    document.getElementById('dynamic-modal-title').innerText = title;
    document.getElementById('dynamic-modal-msg').innerText = message;

    const confirmBtn = document.getElementById('dynamic-modal-btn');
    confirmBtn.style.backgroundColor = buttonColor;

    // kino-clone yung button para matanggal yung mga lumang event listeners bago mag-attach ng bago
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

    newConfirmBtn.onclick = function () {
        closeDynamicModal();
        confirmCallback();
    };

    document.getElementById('dynamic-confirm-overlay').style.display = 'flex';
}

function closeDynamicModal() {
    document.getElementById('dynamic-confirm-overlay').style.display = 'none';
}