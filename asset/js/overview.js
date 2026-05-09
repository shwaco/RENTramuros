import { getPopularAttractions, getRecommendedAttractions } from "../../services/api";

// THE TRANSLATOR DICTIONARY

const routeDictionary = {
    // --- ATTRACTIONS ---
    "fort-santiago":          { db_id: 1, type: "attraction" },
    "casa-manila":            { db_id: 2, type: "attraction" },
    "san-agustin-museum":     { db_id: 3, type: "attraction" },
    "san-agustin-church":     { db_id: 4, type: "attraction" },
    "centro-de-turismo":      { db_id: 5, type: "attraction" },
    "bambike":                { db_id: 6, type: "attraction" },
    "barbaras":               { db_id: 7, type: "attraction" },
    "minor-basilica":         { db_id: 8, type: "attraction" },
    "museo-de-intramuros":    { db_id: 9, type: "attraction" },
    "palacio-del-gobernador": { db_id: 10, type: "attraction" },
    "puerta-del-parian":      { db_id: 11, type: "attraction" },
    "puerta-real-gardens":    { db_id: 12, type: "attraction" },
    "rizal-shrine":           { db_id: 13, type: "attraction" },
    "light-and-sound-museum": { db_id: 14, type: "attraction" },
    "silahis-art":            { db_id: 15, type: "attraction" },

    // --- PACKAGES ---
    "heros-trail":            { db_id: 1, type: "package" },
    "cultural-combo":         { db_id: 2, type: "package" },
    "walled-city-grand-tour": { db_id: 3, type: "package" },
    "bastions-and-walls":     { db_id: 4, type: "package" },
    "sacred-route":           { db_id: 5, type: "package" }
};

// =====================================================================
// STEP 2: INTERCEPT AND FETCH
// =====================================================================
document.addEventListener("DOMContentLoaded", async () => {
    const urlParams = new URLSearchParams(window.location.search);
    
    // IMPORTANT: We use 'let' instead of 'const' so the Magic Converter can change it!
    let currentUrlText = urlParams.get('id')?.toLowerCase();
    const typeParam = urlParams.get('type')?.toLowerCase();

    // =================================================================
    // 🚨 THE MAGIC CONVERTER (OPTION 2) 🚨
    // Intercepts "?type=attraction&id=1" and turns it into "fort-santiago"
    // =================================================================
    if (!isNaN(currentUrlText) && typeParam) {
        const numericId = parseInt(currentUrlText);
        
        // Find the text name in the dictionary using the number and type
        const foundSlug = Object.keys(routeDictionary).find(key => {
            return routeDictionary[key].db_id === numericId && routeDictionary[key].type === typeParam;
        });

        if (foundSlug) {
            currentUrlText = foundSlug; // Swap the number for the readable text
            
            // Rewrite the URL in the browser bar so it looks clean!
            const newUrl = `${window.location.pathname}?id=${foundSlug}`;
            window.history.replaceState(null, '', newUrl); 
        }
    }
    // =================================================================

    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImg");
    const closeBtn = document.querySelector(".close");

    // 1. Look up the URL in our dictionary to see if it is a real page
    const translation = routeDictionary[currentUrlText];
    try {
        let currentData = null;
        let database = null;

        // Only fetch if the URL matches our dictionary
        if (translation) {
            database = await getPopularAttractions();
            
            // If the API failed and returned null, manually trigger the system error!
            if (database === null) {
                throw new Error("API returned null, triggering system failure fallback.");
            }

            if (database) {
                currentData = database[currentUrlText]; 
            }
        }
        // Notice we REMOVED the "throw new Error" here so it doesn't skip the UI fallback!

        // =====================================================================
        // STEP 3: INJECT DATA INTO HTML
        // =====================================================================
        if (currentData) {
            // --- checking if package or attraction ---
            const isPackage = currentData.package_id !== undefined;

            const titleText = isPackage ? currentData.package_name : currentData.attraction_name;   
            document.getElementById("page-title").textContent = `RENTramuros | ${titleText}`;
            document.getElementById("attraction-title").textContent = titleText;
            document.getElementById("attraction-description").textContent = currentData.description;

            const addressContainer = document.querySelector(".attraction-address"); 
            const locationIcon = document.querySelector(".location-icon");
            const addressSpan = document.getElementById("attraction-address");
            const hoursSpan = document.getElementById("attraction-hours");

            let dbImages = [];

            if (isPackage) {
                addressContainer.style.display = "flex"; 
                locationIcon.style.display = "none"; 
                hoursSpan.style.display = "none"; 
                
                if (currentData.attraction_id && Array.isArray(currentData.attraction_id)) {
                    
                    const linkedAttractions = currentData.attraction_id.map(id => {
                        const matchKey = Object.keys(database).find(key => database[key].attraction_id === id);
                        return matchKey ? database[matchKey] : null;
                    }).filter(attr => attr !== null); 

                    // Inject the text itinerary
                    const itineraryNames = linkedAttractions.map(attr => attr.attraction_name);
                    addressSpan.textContent = itineraryNames.join(" • ");

                    // C. Apply the Dynamic Image Rules!
                    const numAttrs = linkedAttractions.length;
                    
                    if (numAttrs >= 4) {
                        dbImages = [
                            linkedAttractions[0].main_img,
                            linkedAttractions[1].main_img,
                            linkedAttractions[2].main_img,
                            linkedAttractions[3].main_img
                        ];
                    } else if (numAttrs === 3) {
                        dbImages = [
                            linkedAttractions[0].main_img,
                            linkedAttractions[1].main_img,
                            linkedAttractions[2].main_img,
                            linkedAttractions[0].mini_one_img
                        ];
                    } else if (numAttrs === 2) {
                        dbImages = [
                            linkedAttractions[0].main_img,
                            linkedAttractions[0].mini_one_img,
                            linkedAttractions[1].main_img,
                            linkedAttractions[1].mini_one_img
                        ];
                    } else if (numAttrs === 1) {
                        dbImages = [
                            linkedAttractions[0].main_img,
                            linkedAttractions[0].mini_one_img,
                            linkedAttractions[0].mini_two_img,
                            linkedAttractions[0].rec_img
                        ];
                    }

                } else {
                    addressSpan.textContent = "Itinerary not available.";
                    dbImages = ["", "", "", ""]; 
                }
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
                
                dbImages = [
                    currentData.main_img,
                    currentData.mini_one_img,
                    currentData.mini_two_img,
                    currentData.rec_img
                ];
            }

            const feeValue = parseFloat(isPackage ? currentData.price : currentData.fee); 
            const feeLabel = isPackage ? "Package Fee" : "Entrance";

            if (feeValue === 0 || isNaN(feeValue)) {
                document.getElementById("attraction-price").textContent = `🎟️ ${feeLabel}: Free`;
            } else {
                document.getElementById("attraction-price").textContent = `🎟️ ${feeLabel}: ₱${Math.round(feeValue)}`;
            }

            const imageBoxes = document.querySelectorAll('.images-grid-container .box img');
            
            dbImages.forEach((imageUrl, index) => {
                if (imageBoxes[index] && imageUrl) {
                    imageBoxes[index].src = imageUrl;

                    imageBoxes[index].addEventListener("click", function() {
                        // This is the bulletproof check!
                        const rawSrc = this.getAttribute("src");
                        
                        if (rawSrc && rawSrc !== "") { 
                            modal.classList.add("show");
                            modalImg.src = this.src;
                        }
                    });
                }
            });

            // FIX #1: Dynamically set the booking button URL!
            const bookBtn = document.querySelector('.book-btn');
        if (bookBtn) {
            bookBtn.onclick = function() {
                // I-check kung true yung pinasa nating variable mula sa PHP
                if (window.IS_LOGGED_IN) {
                    // Kung naka-login, diretso sa Tour Booking page
                    window.location.href = '../tour_page/tours_latest.php';
                } else {
                    // Kung hindi naka-login, ibabato sa Login page
                    window.location.href = '../login_page/login.php';
                }
            };
        }

        } else {
            // FIX #3: Because we didn't throw an error above, this fallback UI will now correctly show!
            document.getElementById("attraction-title").textContent = "Item Not Found";
            document.getElementById("attraction-description").textContent = "The tour or attraction you are looking for does not exist. Please return to the dashboard.";
            document.getElementById("attraction-address").textContent = "";
            document.getElementById("attraction-hours").textContent = "";
            document.getElementById("attraction-price").textContent = "";
            document.querySelector(".location-icon").style.display = "none";
            
            // Hide the booking button if the item doesn't exist
            const bookBtn = document.querySelector('.book-btn');
            if (bookBtn) bookBtn.style.display = 'none';
        }
    } catch (error) {
        console.error("Error loading the data:", error);
        document.getElementById("attraction-title").textContent = "System Error";
        document.getElementById("attraction-description").textContent = "Could not connect to the database. Please try again later.";
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
