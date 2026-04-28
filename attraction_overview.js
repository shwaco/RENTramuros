// fetching json data
document.addEventListener("DOMContentLoaded", async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const currentId = urlParams.get('id')?.toLowerCase();

    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImg");
    const closeBtn = document.querySelector(".close");

    try {
        const response = await fetch('attraction_overview_data.json');
        const database = await response.json();
        const currentData = database[currentId];

        if (currentData) {
            // --- DETECTIVE LOGIC: Is it a package or an attraction? ---
            const isPackage = currentData.package_id !== undefined;

            // 1. TEXT INJECTION: Swap between attraction_name and package_name
            const titleText = isPackage ? currentData.package_name : currentData.attraction_name;   
            document.getElementById("page-title").textContent = `RENTramuros | ${titleText}`;
            document.getElementById("attraction-title").textContent = titleText;
            document.getElementById("attraction-description").textContent = currentData.description;

            // 2. UI TOGGLES: Icon, Itinerary vs Address, and Hours
            const addressContainer = document.querySelector(".attraction-address"); 
            const locationIcon = document.querySelector(".location-icon");
            const addressSpan = document.getElementById("attraction-address");
            const hoursSpan = document.getElementById("attraction-hours");

            if (isPackage) {
                addressContainer.style.display = "flex"; 
                locationIcon.style.display = "none"; 
                
                // --- NEW RELATIONAL LOGIC (The "Junction Table" query) ---
                if (currentData.itinerary_ids && Array.isArray(currentData.itinerary_ids)) {
                    // Map the array of numbers to their actual attraction names
                    const itineraryNames = currentData.itinerary_ids.map(id => {
                        // Search the whole database to find the object with the matching attraction_id
                        const matchKey = Object.keys(database).find(key => database[key].attraction_id === id);
                        return matchKey ? database[matchKey].attraction_name : "Unknown Attraction";
                    });
                    
                    // Join them together with hyphens and inject!
                    addressSpan.textContent = itineraryNames.join(" • ");
                } else {
                    addressSpan.textContent = "Itinerary not available.";
                }
                // ---------------------------------------------------------

                hoursSpan.style.display = "none"; 
            } else {
                if (currentData.address) {
                    addressContainer.style.display = "flex";
                    locationIcon.style.display = "inline"; 
                    addressSpan.textContent = currentData.address;
                } else {
                    addressContainer.style.display = "none"; 
                }

                hoursSpan.style.display = "inline"; 
                hoursSpan.textContent = `🕒 Open: ${currentData.schedule}`;
            }

            // 3. FEE LOGIC: Swap between price and fee, update the label
            const feeValue = parseFloat(isPackage ? currentData.price : currentData.fee); 
            const feeLabel = isPackage ? "Package Fee" : "Entrance";

            if (feeValue === 0 || isNaN(feeValue)) {
                document.getElementById("attraction-price").textContent = `🎟️ ${feeLabel}: Free`;
            } else {
                document.getElementById("attraction-price").textContent = `🎟️ ${feeLabel}: ₱${feeValue.toFixed(2)}`;
            }

            // 4. IMAGE INJECTION: Because of Option 1, both have these 4 columns!
            const imageBoxes = document.querySelectorAll('.images-grid-container .box img');
            const dbImages = [
                currentData.main_img,
                currentData.mini_one_img,
                currentData.mini_two_img,
                currentData.rec_img
            ];

            dbImages.forEach((imageUrl, index) => {
                if (imageBoxes[index] && imageUrl) {
                    imageBoxes[index].src = imageUrl;

                    // MODAL LOGIC
                    imageBoxes[index].addEventListener("click", function() {
                        if (this.src && !this.src.includes("index.html")) { 
                            modal.classList.add("show");
                            modalImg.src = this.src;
                        }
                    });
                }
            });
        } else {
            // Error handling
            document.getElementById("attraction-title").textContent = "Item Not Found";
            document.getElementById("attraction-description").textContent = "Please return to the dashboard.";
            document.getElementById("attraction-address").textContent = "";
            document.getElementById("attraction-hours").textContent = "";
            document.getElementById("attraction-price").textContent = "";
            document.querySelector(".location-icon").style.display = "none";
        }
    } catch (error) {
        console.error("Error loading the data:", error);
        document.getElementById("attraction-title").textContent = "Error Loading Data";
    }

    // MODAL CLOSING LOGIC 
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