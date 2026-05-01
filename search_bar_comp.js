// IMPORT THE DATA FETCHER
import { fetchAttractionData } from './search_bar_api.js';

function renderAttractionCards(attractionsList) {
    const attractionsContainer = document.querySelector('.intramuros-attractions-lists');
    attractionsContainer.innerHTML = ''; 
    
    attractionsList.forEach(attraction => {
        // Updated to match the new JSON keys: attraction_id, main_img, attraction_name
        const cardHTML = `
            <div class="attraction-card" data-id="${attraction.attraction_id}">
                <img src="${attraction.main_img}" alt="${attraction.attraction_name}">
                <span class="attraction-name">${attraction.attraction_name}</span>
            </div>
        `;
        attractionsContainer.innerHTML += cardHTML;
    });
}

// THE MAIN COORDINATOR
document.addEventListener("DOMContentLoaded", async () => {
    const searchInput = document.getElementById("search-input");
    const searchBtn = document.getElementById("search-btn");
    const dropdownWrapper = document.querySelector(".search-dropdown-wrapper");
    const historyContainer = document.querySelector(".search-history-lists");

    let isSearchLocked = false;
    
    const attractionData = await fetchAttractionData();
    renderAttractionCards(attractionData);

   // CLICK EVENTS TO CARDS ---
    const allCards = document.querySelectorAll('.attraction-card');

    allCards.forEach((card) => {
        const attractionName = card.querySelector('.attraction-name').innerText;

        // 1. HOVER EVENT
        card.addEventListener("mouseenter", () => {
            if (!isSearchLocked) {
                searchInput.value = attractionName;
            }
        });

        // 2. CLICK EVENT
        card.addEventListener("click", () => {
            isSearchLocked = true; // Engage the lock
            searchInput.value = attractionName;
            
            // Trigger the live-filter to isolate the card
            searchInput.dispatchEvent(new Event('input')); 
            searchInput.focus(); 
        });
    });

    // LIVE SEARCH FILTERING
    searchInput.addEventListener('input', (event) => {
        if (event.isTrusted) {
            isSearchLocked = false;
        }

        dropdownWrapper.classList.add("active"); 
        const searchTerm = event.target.value.toLowerCase();
            if (attractionName.includes(searchTerm)) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    

    // SHOW/HIDE DROPDOWN LOGIC
    searchInput.addEventListener("focus", () => {
        dropdownWrapper.classList.add("active");
    });

    document.addEventListener("click", (event) => {
        const isClickInsideSearch = searchInput.contains(event.target);
        const isClickInsideDropdown = dropdownWrapper.contains(event.target);

        if (!isClickInsideSearch && !isClickInsideDropdown) {
            dropdownWrapper.classList.remove("active");
        }
    });

    // SEARCH HISTORY LOGIC
    function renderHistoryPills() {
        const history = JSON.parse(localStorage.getItem("intramurosSearchHistory")) || [];
        historyContainer.innerHTML = "";

        history.forEach((term) => {
            const pill = document.createElement("div");
            pill.classList.add("history-pill");
            pill.innerText = term;
            
            // 1. HOVER EVENT: Preview text ONLY if the search bar is not locked
            pill.addEventListener("mouseenter", () => {
                if (!isSearchLocked) {
                    searchInput.value = term;
                }
            });

            // 2. CLICK EVENT: Lock the text and filter the grid
            pill.addEventListener("click", () => {
                isSearchLocked = true; // Engage the lock!
                searchInput.value = term;
                
                // Triggers the live-filter to hide the other cards
                searchInput.dispatchEvent(new Event('input')); 
                searchInput.focus(); 
            });

            historyContainer.appendChild(pill);
        });
    }

    function saveSearchToHistory(term) {
        if (!term.trim()) return;
        let history = JSON.parse(localStorage.getItem("intramurosSearchHistory")) || [];
        history = history.filter(item => item.toLowerCase() !== term.toLowerCase());
        history.unshift(term.trim());
        if (history.length > 5) history.pop();
        
        localStorage.setItem("intramurosSearchHistory", JSON.stringify(history));
        renderHistoryPills();
    }

    // Initialize history on load
    renderHistoryPills();

    // SUBMIT LOGIC (ENTER KEY & SEARCH BUTTON)
    function handleSearchSubmit() {
        const searchTerm = searchInput.value.trim();
        if (!searchTerm) return; 

        let foundId = null;

        allCards.forEach(card => {
            const cardName = card.querySelector('.attraction-name').innerText;
            if (cardName.toLowerCase() === searchTerm.toLowerCase()) {
                foundId = card.getAttribute('data-id');
            }
        });

        saveSearchToHistory(searchTerm);

        if (foundId) {
            window.location.href = `overview.html?id=${foundId}`;
        } else {
            dropdownWrapper.classList.remove("active");
        }
    }

    searchBtn.addEventListener("click", () => {
        handleSearchSubmit();
    });

    searchInput.addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
            handleSearchSubmit();
        }
    });
});