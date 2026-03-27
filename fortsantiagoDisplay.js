const modal = document.getElementById("imageModal");
const modalImg = document.getElementById("modalImg");
const closeBtn = document.querySelector(".close");

document.querySelectorAll(".box img").forEach(img => {
    img.addEventListener("click", function() {
        modal.classList.add("show");
        modalImg.src = this.src;
    });
});

// Close modal when the Escape key is pressed
document.addEventListener("keydown", function(e) {
    if (e.key === "Escape" && modal.classList.contains("show")) {
        modal.classList.remove("show");
    }
});

closeBtn.onclick = function() {
    modal.classList.remove("show");
}

modal.onclick = function(e) {
    if (e.target === modal) {
        modal.classList.remove("show");
    }
}