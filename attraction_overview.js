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

           // 2. UI TOGGLES & RELATIONAL MAPPING
            const addressContainer = document.querySelector(".attraction-address"); 
            const locationIcon = document.querySelector(".location-icon");
            const addressSpan = document.getElementById("attraction-address");
            const hoursSpan = document.getElementById("attraction-hours");

            let dbImages = []; // We will fill this dynamically!

            if (isPackage) {
                addressContainer.style.display = "flex"; 
                locationIcon.style.display = "none"; 
                hoursSpan.style.display = "none"; 
                
                // --- RELATIONAL LOGIC FOR TEXT AND IMAGES ---
                if (currentData.attraction_ids && Array.isArray(currentData.attraction_ids)) {
                    
                    // A. Fetch the actual attraction objects from the database
                    const linkedAttractions = currentData.attraction_ids.map(id => {
                        const matchKey = Object.keys(database).find(key => database[key].attraction_id === id);
                        return matchKey ? database[matchKey] : null;
                    }).filter(attr => attr !== null); // Filter out any missing data

                    // B. Inject the text itinerary
                    const itineraryNames = linkedAttractions.map(attr => attr.attraction_name);
                    addressSpan.textContent = itineraryNames.join(" • ");

                    // C. Apply the Dynamic Image Rules!
                    const numAttrs = linkedAttractions.length;
                    
                    if (numAttrs >= 4) {
                        // 4 or more: Grab the main_img from the first 4
                        dbImages = [
                            linkedAttractions[0].main_img,
                            linkedAttractions[1].main_img,
                            linkedAttractions[2].main_img,
                            linkedAttractions[3].main_img
                        ];
                    } else if (numAttrs === 3) {
                        // Exactly 3: Grab all 3 main_imgs, plus the mini_one from the first attraction
                        dbImages = [
                            linkedAttractions[0].main_img,
                            linkedAttractions[1].main_img,
                            linkedAttractions[2].main_img,
                            linkedAttractions[0].mini_one_img
                        ];
                    } else if (numAttrs === 2) {
                        // Exactly 2: Grab the main and mini_one from both
                        dbImages = [
                            linkedAttractions[0].main_img,
                            linkedAttractions[0].mini_one_img,
                            linkedAttractions[1].main_img,
                            linkedAttractions[1].mini_one_img
                        ];
                    } else if (numAttrs === 1) {
                        // Fallback just in case a package only has 1 attraction
                        dbImages = [
                            linkedAttractions[0].main_img,
                            linkedAttractions[0].mini_one_img,
                            linkedAttractions[0].mini_two_img,
                            linkedAttractions[0].rec_img
                        ];
                    }

                } else {
                    addressSpan.textContent = "Itinerary not available.";
                    dbImages = ["", "", "", ""]; // Blank fallback if array is missing
                }
            } else {
                // If Attraction: Standard text and image injection
                if (currentData.address) {
                    addressContainer.style.display = "flex";
                    locationIcon.style.display = "inline"; 
                    addressSpan.textContent = currentData.address;
                } else {
                    addressContainer.style.display = "none"; 
                }

                hoursSpan.style.display = "inline"; 
                hoursSpan.textContent = `🕒 Open: ${currentData.schedule}`;

                // Standard image fetch for standalone attractions
                dbImages = [
                    currentData.main_img,
                    currentData.mini_one_img,
                    currentData.mini_two_img,
                    currentData.rec_img
                ];
            }

            // 3. FEE LOGIC: Handle 0 vs Decimals
            const feeValue = parseFloat(isPackage ? currentData.price : currentData.fee); 
            const feeLabel = isPackage ? "Package Fee" : "Entrance";

            if (feeValue === 0 || isNaN(feeValue)) {
                document.getElementById("attraction-price").textContent = `🎟️ ${feeLabel}: Free`;
            } else {
                document.getElementById("attraction-price").textContent = `🎟️ ${feeLabel}: ₱${feeValue.toFixed(2)}`;
            }

            // 4. IMAGE INJECTION & MODAL
            const imageBoxes = document.querySelectorAll('.images-grid-container .box img');
            
            dbImages.forEach((imageUrl, index) => {
                if (imageBoxes[index] && imageUrl) {
                    imageBoxes[index].src = imageUrl;

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