// fetching json data
document.addEventListener("DOMContentLoaded", async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const currentId = urlParams.get('id');

    // Move modal variables to the top so they are ready
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImg");
    const closeBtn = document.querySelector(".close");

    try {
        const response = await fetch('attraction_overview_data.json');
        const attractionDatabase = await response.json();

        const currentData = attractionDatabase[currentId];

        if (currentData) {
            // 1. TEXT INJECTION: Mapped to your new database column names!
            document.getElementById("page-title").textContent = `RENTramuros | ${currentData.attraction_name}`;
            document.getElementById("attraction-title").textContent = currentData.attraction_name;
document.getElementById("attraction-description").textContent = currentData.description;            document.getElementById("attraction-hours").textContent = `🕒 Open: ${currentData.schedule}`;
            
            // Assuming these are still in your DB or hardcoded
            document.getElementById("attraction-address").textContent = currentData.address || "Intramuros, Manila";
            document.getElementById("attraction-price").textContent = `🎟️ Entrance: ${currentData.price || "TBA"}`;

            // 2. IMAGE INJECTION: Pack the 4 explicit DB columns into an array in the exact order you want them rendered
            const imageBoxes = document.querySelectorAll('.images-grid-container .box img');
            
            const dbImages = [
                currentData.main_img,
                currentData.mini_one_img,
                currentData.mini_two_img,
                currentData.rec_img
            ];

            // --- NEW FEE LOGIC (WITH DECIMALS) ---
            // 1. Grab the pure number from the JSON and make sure it's read as a math number (parseFloat)
            const feeValue = parseFloat(currentData.fee); 
            
            // 2. Check if it's 0. If yes, say "Free". 
            if (feeValue === 0) {
                document.getElementById("attraction-price").textContent = `🎟️ Entrance: Free`;
            } else {
                // 3. If it costs money, add the Peso sign and force 2 decimal places!
                document.getElementById("attraction-price").textContent = `🎟️ Entrance: ₱${feeValue.toFixed(2)}`;
            }

            dbImages.forEach((imageUrl, index) => {
                if (imageBoxes[index] && imageUrl) {
                    imageBoxes[index].src = imageUrl;

                    // 3. MODAL LOGIC: Attach the click event immediately as the image loads
                    imageBoxes[index].addEventListener("click", function() {
                        if (this.src && !this.src.includes("index.html")) { 
                            modal.classList.add("show");
                            modalImg.src = this.src;
                        }
                    });
                }
            });
        } else {
            // error handling if attraction json data is missing
            document.getElementById("attraction-title").textContent = "Attraction Not Found";
            document.getElementById("attraction-description").textContent = "Please return to the dashboard.";
            document.getElementById("attraction-address").textContent = "";
            document.getElementById("attraction-hours").textContent = "";
            document.getElementById("attraction-price").textContent = "";
        }
    } catch (error) {
        console.error("Error loading the tour data:", error);
        document.getElementById("attraction-title").textContent = "Error Loading Data";
    }

    // ==========================================
    // MODAL CLOSING LOGIC 
    // ==========================================
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