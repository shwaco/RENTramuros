    // -------- CALENDAR LOGIC ---------
    document.addEventListener("DOMContentLoaded", () => {
        const monthDisplay = document.querySelector('.current-month');
        const calendarGrid = document.querySelector('.calendar-grid');
        const prevBtn = document.querySelectorAll('.calendar-header .nav-arrow')[0];
        const nextBtn = document.querySelectorAll('.calendar-header .nav-arrow')[1];

        let currentDate = new Date(); 
        let today = new Date();
        today.setHours(0, 0, 0, 0);

        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

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

                let cellDate = new Date(year, month, i);

                
                dayCell.innerHTML = `
                    <span class="day-number">${i}</span>
                    <div class="day-tooltip">
                        <p class="tooltip-header">${monthNames[month]} ${i}, ${year}</p>
                        <div class="tooltip-beak"></div>
                    </div>
                `;

                if (cellDate <= today) {
                    dayCell.classList.add('disabled-day');
                } else {
                    dayCell.addEventListener('click', () => {
                        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
                        dayCell.classList.add('selected');
                        document.getElementById('date-display').innerText = `${monthNames[month]} ${i}, ${year}`;
                    });
                }
                calendarGrid.appendChild(dayCell);
            }
        }

        prevBtn.addEventListener('click', (e) => {
            e.preventDefault(); 
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });

        nextBtn.addEventListener('click', (e) => {
            e.preventDefault(); 
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });

        renderCalendar();
    });