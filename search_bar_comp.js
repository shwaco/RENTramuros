document.addEventListener("DOMContentLoaded", () => {
    // ==========================================
    // 1. GRAB HTML ELEMENTS
    // ==========================================
    const searchInput = document.getElementById("search-input");
    const dropdownWrapper = document.querySelector(".search-dropdown-wrapper");
    
    // Using querySelectorAll grabs every element with these classes as a list
    const allCards = document.querySelectorAll('.attraction-card'); 
    const historyPills = document.querySelectorAll('.history-pill'); 

    // ==========================================
    // 2. SHOW/HIDE DROPDOWN LOGIC
    // ==========================================
    // SHOW: When user clicks inside the search input
    searchInput.addEventListener("focus", () => {
        dropdownWrapper.classList.add("active");
    });

    // HIDE: When user clicks anywhere outside the input or dropdown
    document.addEventListener("click", (event) => {
        const isClickInsideSearch = searchInput.contains(event.target);
        const isClickInsideDropdown = dropdownWrapper.contains(event.target);

        if (!isClickInsideSearch && !isClickInsideDropdown) {
            dropdownWrapper.classList.remove("active");
        }
    });

    // ==========================================
    // 3. DYNAMIC ATTRACTION CARD LOGIC
    // ==========================================
    allCards.forEach((card) => {
        card.addEventListener('click', () => {
            // Read the dynamic data-id you set in the HTML
            const cardId = card.getAttribute('data-id');
            
            // Find the specific name of the attraction clicked
            const attractionName = card.querySelector('.attraction-name').innerText;
            
            console.log(`Loading data for ID: ${cardId}`);
            
            // Update the search bar text and close the dropdown
            searchInput.value = attractionName;
            dropdownWrapper.classList.remove("active");
            
            // NOTE: Here is where you would normally trigger a page change 
            // or fetch data from your database using that cardId!
        });
    });

    // ==========================================
    // 4. HISTORY PILL LOGIC
    // ==========================================
    historyPills.forEach((pill) => {
        pill.addEventListener('click', () => {
            // When a history pill is clicked, fill the search bar and close dropdown
            searchInput.value = pill.innerText;
            dropdownWrapper.classList.remove("active");
        });
    });

    // ==========================================
    // 5. LIVE SEARCH FILTERING
    // ==========================================
    // This listens for every single keystroke as the user types
    searchInput.addEventListener('input', (event) => {
        const searchTerm = event.target.value.toLowerCase();
        
        // Loop through all cards and hide the ones that don't match the search
        allCards.forEach((card) => {
            const attractionName = card.querySelector('.attraction-name').innerText.toLowerCase();
            
            if (attractionName.includes(searchTerm)) {
                card.style.display = "block"; // Keep it visible
            } else {
                card.style.display = "none";  // Hide it
            }
        });
    });
});