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