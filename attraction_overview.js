// fetching json data
document.addEventListener("DOMContentLoaded", async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const currentId = urlParams.get('id');

    try {
        const response = await fetch('attraction_overview_data.json');
        const attractionDatabase = await response.json();

        const currentData = attractionDatabase[currentId];

        if (currentData) {
            document.getElementById("page-title").textContent = `RENTramuros | ${currentData.title}`;
            document.getElementById("attraction-title").textContent = currentData.title;
            document.getElementById("attraction-address").textContent = currentData.address;
            document.getElementById("attraction-overview").textContent = currentData.overview;
            document.getElementById("attraction-hours").textContent = `🕒 Open: ${currentData.hours}`;
            document.getElementById("attraction-price").textContent = `🎟️ Entrance: ${currentData.price}`;

            const imageBoxes = document.querySelectorAll('.images-grid-container .box img');
            currentData.images.forEach((imageUrl, index) => {
                if (imageBoxes[index]) {
                    imageBoxes[index].src = imageUrl;
                }
            });
        } else {
            // error handling if attraction json data is missing
            document.getElementById("attraction-title").textContent = "Attraction Not Found";
            document.getElementById("attraction-overview").textContent = "Please return to the dashboard.";
            document.getElementById("attraction-address").textContent = "";
            document.getElementById("attraction-hours").textContent = "";
            document.getElementById("attraction-price").textContent = "";
        }
    } catch (error) {
        console.error("Error loading the tour data:", error);
        document.getElementById("attraction-title").textContent = "Error Loading Data";
    }

    // images grid modal
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImg");
    const closeBtn = document.querySelector(".close");

    setTimeout(() => {
        document.querySelectorAll(".images-grid-container .box img").forEach(img => {
            img.addEventListener("click", function() {
                if (this.src && !this.src.includes("index.html")) { 
                    modal.classList.add("show");
                    modalImg.src = this.src;
                }
            });
        });
    }, 200);

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
});