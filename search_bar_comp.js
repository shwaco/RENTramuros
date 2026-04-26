document.addEventListener("DOMContentLoaded", () => {

    const searchInput = document.getElementById("search-input");
    const searchBtn = document.getElementById("search-btn");
    const dropdownWrapper = document.querySelector(".search-dropdown-wrapper");
    const historyContainer = document.querySelector(".search-history-lists");
    const attractionsContainer = document.querySelector('.intramuros-attractions-lists');


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
            
            pill.addEventListener("click", () => {
                // 1. Look for a card on the screen that matches this history term perfectly
                const allCards = document.querySelectorAll('.attraction-card');
                let foundId = null;

                allCards.forEach(card => {
                    const cardName = card.querySelector('.attraction-name').innerText;
                    if (cardName.toLowerCase() === term.toLowerCase()) {
                        foundId = card.getAttribute('data-id');
                    }
                });

                // for directing to the overview page with the correct ID in the URL
                if (foundId) {
                    window.location.href = `overview.html?id=${foundId}`;
                } else {
                    
                    searchInput.value = term;
                    dropdownWrapper.classList.remove("active");
                }
            });

            historyContainer.appendChild(pill);
        });
    }

    function saveSearchToHistory(term) {
        if (!term.trim()) return;

        let history = JSON.parse(localStorage.getItem("intramurosSearchHistory")) || [];
        history = history.filter(item => item.toLowerCase() !== term.toLowerCase());
        history.unshift(term.trim());

        if (history.length > 5) {
            history.pop();
        }

        localStorage.setItem("intramurosSearchHistory", JSON.stringify(history));
        renderHistoryPills();
    }

    // Load history
    renderHistoryPills();

    searchBtn.addEventListener("click", () => {
        saveSearchToHistory(searchInput.value);
        dropdownWrapper.classList.remove("active");
    });

    searchInput.addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
            saveSearchToHistory(searchInput.value);
            dropdownWrapper.classList.remove("active");
        }
    });

    // FETCH DATA & INITIALIZE CARDS
    fetch('search_attraction_data.json')
        .then(response => response.json())
        .then(data => {
            
            data.forEach(attraction => {
                const cardHTML = `
                    <div class="attraction-card" data-id="${attraction.id}">
                        <img src="${attraction.image}" alt="${attraction.name}">
                        <span class="attraction-name">${attraction.name}</span>
                    </div>
                `;
                attractionsContainer.innerHTML += cardHTML;
            });

            const allCards = document.querySelectorAll('.attraction-card');

            allCards.forEach((card) => {
                card.addEventListener('click', () => {
                    const attractionName = card.querySelector('.attraction-name').innerText;

                    // for directing to the overview page with the correct ID in the URL
                    const cardId = card.getAttribute('data-id');
                    
                    saveSearchToHistory(attractionName);
                    
                    window.location.href = `overview.html?id=${cardId}`;
                    
                    searchInput.value = attractionName;
                    dropdownWrapper.classList.remove("active");
                });
            });
            // SEARCH FILTERING LOGIC
            searchInput.addEventListener('input', (event) => {
                dropdownWrapper.classList.add("active"); 
                const searchTerm = event.target.value.toLowerCase();
                
                allCards.forEach((card) => {
                    const attractionName = card.querySelector('.attraction-name').innerText.toLowerCase();
                    
                    if (attractionName.includes(searchTerm)) {
                        card.style.display = "block";
                    } else {
                        card.style.display = "none";
                    }
                });
            });
            
        })
        .catch(error => {
            console.error("Error loading the attraction data:", error);
        });
});