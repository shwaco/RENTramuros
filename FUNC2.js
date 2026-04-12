const monthDisplay = document.querySelector('.current-month');
const calendarGrid = document.querySelector('.calendar-grid');
const [prevBtn, nextBtn] = document.querySelectorAll('.nav-arrow');

let currentDate = new Date(2026, 2, 1); 

const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

const rentramurosData = {
    "2026-03-16": { price: "22,541", event: "Fort Santiago Heritage Walk" },
    "2026-03-17": { price: "15,200", event: "Bambike Sunset Tour" },
    "2026-03-25": { price: "18,000", event: "San Agustin Museum Entry" },
    "2026-04-05": { price: "25,000", event: "Intramuros Night Gala" }
};

function renderCalendar() {
    calendarGrid.innerHTML = '';
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    monthDisplay.innerText = `${monthNames[month]} ${year}`;

    const firstDayIndex = new Date(year, month, 1).getDay();
    const lastDay = new Date(year, month + 1, 0).getDate();

    for (let x = 0; x < firstDayIndex; x++) {
        calendarGrid.appendChild(document.createElement('div'));
    }

    for (let i = 1; i <= lastDay; i++) {
        const dayCell = document.createElement('div');
        dayCell.classList.add('calendar-day');

        const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        const dayInfo = rentramurosData[dateKey] || { price: "---", event: "Available for booking" };

        dayCell.innerHTML = `
            <span class="day-number">${i}</span>
            <div class="day-tooltip">
                <p class="tooltip-header">${monthNames[month]} ${i}, ${year}</p>
                <div class="tooltip-beak"></div>
            </div>
        `;

        dayCell.addEventListener('click', () => {
            document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
            dayCell.classList.add('selected');
        });

        calendarGrid.appendChild(dayCell);
    }
}


prevBtn.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});

nextBtn.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});

renderCalendar();