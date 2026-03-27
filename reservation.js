const form = document.getElementById("ReservationForm");
const popup = document.getElementById("popup");
const modalText = document.getElementById("modalText");
const closeBtn = document.getElementById("closeBtn");
const phone = document.getElementById("phone").value;

form.addEventListener("submit", function(e) {
    e.preventDefault(); // prevent page reload
    
    // Get input values
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const phone = document.getElementById("phone").value;
    
    if (!/^\d{11}$/.test(phone)) {
        alert("Phone number must contain exactly 11 digits.");
        return;
}

    // Update modal content
    modalText.innerHTML = `
        <div class="modal-row">
            <span class="modal-label">Name:</span>
            <span class="modal-value">${name}</span>
        </div>
        <div class="modal-row">
            <span class="modal-label">Email:</span>
            <span class="modal-value">${email}</span>
        </div>
        <div class="modal-row">
            <span class="modal-label">Phone:</span>
            <span class="modal-value">${phone}</span>
        </div>
    `;
    
    // Show the modal
    popup.style.display = "flex";
});

closeBtn.addEventListener("click", function() {
    popup.style.display = "none";
});



function closePopup() {
    popup.style.display = "none";
}

