let currentStep = 1;

function updateForm() {
    // 1. SLIDE THE CONTAINERS
    const track = document.getElementById('sliderTrack');
    // Calculates how far left to push the track: 0%, -33.333%, or -66.666%
    const translation = (currentStep - 1) * -33.333; 
    track.style.transform = `translateX(${translation}%)`;

    // 2. RESET ALL TOP CIRCLES TO WHITE (INACTIVE)
    document.querySelector('.circle-1').style.backgroundColor = '#ffffff';
    document.querySelector('.circle-1').style.color = '#000000';
    document.querySelector('.progress-bar-1').style.backgroundColor = '#ffffff';
    
    document.querySelector('.circle-2').style.backgroundColor = '#ffffff';
    document.querySelector('.circle-2').style.color = '#000000';
    document.querySelector('.progress-bar-2').style.backgroundColor = '#ffffff';
    
    document.querySelector('.circle-3').style.backgroundColor = '#ffffff';
    document.querySelector('.circle-3').style.color = '#000000';

    // 3. COLOR THE ACTIVE STEPS RED
    if (currentStep >= 1) {
        document.querySelector('.circle-1').style.backgroundColor = '#8A2814';
        document.querySelector('.circle-1').style.color = '#ffffff';
    }
    if (currentStep >= 2) {
        document.querySelector('.progress-bar-1').style.backgroundColor = '#8A2814';
        document.querySelector('.circle-2').style.backgroundColor = '#8A2814';
        document.querySelector('.circle-2').style.color = '#ffffff';
    }
    if (currentStep >= 3) {
        document.querySelector('.progress-bar-2').style.backgroundColor = '#8A2814';
        document.querySelector('.circle-3').style.backgroundColor = '#8A2814';
        document.querySelector('.circle-3').style.color = '#ffffff';
    }
}

function nextStep() {
    if (currentStep < 3) {
        currentStep++;
        updateForm();
        window.scrollTo({ top: 0, behavior: 'smooth' }); // Scrolls back to top on next
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        updateForm();
        window.scrollTo({ top: 0, behavior: 'smooth' }); // Scrolls back to top on prev
    }
}

// Run once immediately when the page loads so Step 1 is colored red!
updateForm(); 


// 1. Add a variable at the top to remember their choice
let isPackageSelected = null; 

// 2. This function ONLY highlights the button they clicked
function selectPackageOption(wantsPackage) {
    isPackageSelected = wantsPackage; // Save their choice

    // Get the buttons
    const btnYes = document.getElementById('btn-yes');
    const btnNo = document.getElementById('btn-no');

    // Remove the red highlight from both buttons
    btnYes.classList.remove('active-selection');
    btnNo.classList.remove('active-selection');

    // Add the red highlight to the one they just clicked
    if (wantsPackage === true) {
        btnYes.classList.add('active-selection');
    } else {
        btnNo.classList.add('active-selection');
    }
}

// 3. Update nextStep to check their answer BEFORE sliding
function nextStep() {
    // If they are on Step 1, make sure they picked Yes or No
    if (currentStep === 1) {
        if (isPackageSelected === null) {
            alert("Please select YES or NO for the package before continuing.");
            return; // Stops the slider from moving!
        }

        // They made a choice. Let's arrange Step 2 before we slide to it.
        const pkgDiv = document.getElementById('step2Packages');
        const customDiv = document.getElementById('step2Custom');

        if (isPackageSelected === true) {
            pkgDiv.style.display = 'flex';
            customDiv.style.display = 'none';
        } else {
            pkgDiv.style.display = 'none';
            customDiv.style.display = 'flex';
        }
    }

    // Normal sliding logic
    if (currentStep < 3) {
        currentStep++;
        updateForm();
        window.scrollTo({ top: 0, behavior: 'smooth' }); 
    }
}


// time selection
document.addEventListener("DOMContentLoaded", () => {
    const hourCol = document.getElementById("hour-column");
    const minCol = document.getElementById("minute-column");
    const timeSelectBtn = document.getElementById("time-select-btn");
    const timeMenu = document.getElementById("time-menu");
    const timeDisplay = document.getElementById("time-display");
    const confirmBtn = document.getElementById("confirm-time-btn");

    let selectedHour = "06";
    let selectedMinute = "00";
    let selectedAmPm = "AM";

    // Generate Hours (01 to 12)
    for (let i = 1; i <= 12; i++) {
        let val = i < 10 ? "0" + i : i.toString();
        let div = document.createElement("div");
        div.className = `time-option hour-option ${val === selectedHour ? 'selected' : ''}`;
        div.innerText = val;
        div.dataset.val = val;
        div.dataset.type = "hour";
        hourCol.appendChild(div);
    }

    // Generate Minutes (00 to 59)
    for (let i = 0; i <= 59; i++) {
        let val = i < 10 ? "0" + i : i.toString();
        let div = document.createElement("div");
        div.className = `time-option minute-option ${val === selectedMinute ? 'selected' : ''}`;
        div.innerText = val;
        div.dataset.val = val;
        div.dataset.type = "minute";
        minCol.appendChild(div);
    }

    // Handle Clicks
    timeMenu.addEventListener("click", (e) => {
        if (e.target.classList.contains("time-option")) {
            let type = e.target.dataset.type;
            let val = e.target.dataset.val;

            let siblings = e.target.parentElement.querySelectorAll('.time-option');
            siblings.forEach(el => el.classList.remove("selected"));
            e.target.classList.add("selected");

            if (type === "hour") selectedHour = val;
            if (type === "minute") selectedMinute = val;
            if (type === "ampm") selectedAmPm = val;
        }
    });

    // Toggle Menu
    timeSelectBtn.addEventListener("click", () => {
        timeMenu.classList.toggle("show");
    });

    // Confirm Button
    confirmBtn.addEventListener("click", () => {
        timeDisplay.innerText = `${selectedHour}:${selectedMinute} ${selectedAmPm}`;
        timeMenu.classList.remove("show");
    });
});


// --- DATE CALENDAR POPUP LOGIC ---
document.addEventListener("DOMContentLoaded", () => {
    const dateSelectBtn = document.getElementById("date-select-btn");
    const calendarPopup = document.getElementById("calendar-popup");
    const dateDisplay = document.getElementById("date-display");

    // 1. Toggle calendar visibility when the date button is clicked
    dateSelectBtn.addEventListener("click", () => {
        calendarPopup.classList.toggle("show");
    });

    // 2. Update the button text when a date is picked
    calendarPopup.addEventListener("click", (e) => {
        // Find the closest calendar-day element that was clicked
        const dayCell = e.target.closest('.calendar-day');
        
        if (dayCell) {
            // Grab the day number from the clicked cell
            const dayNumber = dayCell.querySelector('.day-number').innerText;
            // Grab the current month and year from the calendar header
            const currentMonthYear = document.querySelector('.current-month').innerText;
            
            // Split "March 2026" into "March" and "2026" to format the text nicely
            const [month, year] = currentMonthYear.split(' ');
            
            // Update the display text to match the format: Month DD, YYYY
            dateDisplay.innerText = `${month} ${dayNumber}, ${year}`;
            
            // Automatically hide the calendar popup after 150ms 
            // (The tiny delay lets the user see the red selection circle first!)
            setTimeout(() => {
                 calendarPopup.classList.remove("show");
            }, 150); 
        }
    });
});