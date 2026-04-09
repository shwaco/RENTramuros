// --- ADULTS COUNTER ---

// 1. Grab the exact buttons and the number display from the HTML
const adultMinusBtn = document.querySelector('.adult-quantity .minus');
const adultPlusBtn = document.querySelector('.adult-quantity .plus');
const adultCountDisplay = document.querySelector('.adult-count');

// 2. Set the starting number
let adultCount = 0;

// 3. Tell the plus button what to do when clicked
adultPlusBtn.addEventListener('click', function() {
    adultCount++; // Add 1 to the count
    adultCountDisplay.textContent = adultCount; // Update the screen
});

// 4. Tell the minus button what to do when clicked
adultMinusBtn.addEventListener('click', function() {
    if (adultCount > 0) { // Check to make sure we don't go below 0!
        adultCount--; // Subtract 1 from the count
        adultCountDisplay.textContent = adultCount; // Update the screen
    }
});


// --- CHILDREN COUNTER ---

// 1. Grab the exact buttons and the number display from the HTML
const childrenMinusBtn = document.querySelector('.children-quantity .minus');
const childrenPlusBtn = document.querySelector('.children-quantity .plus');
const childrenCountDisplay = document.querySelector('.children-count');

// 2. Set the starting number
let childrenCount = 0;

// 3. Tell the plus button what to do when clicked
childrenPlusBtn.addEventListener('click', function() {
    childrenCount++; 
    childrenCountDisplay.textContent = childrenCount; 
});

// 4. Tell the minus button what to do when clicked
childrenMinusBtn.addEventListener('click', function() {
    if (childrenCount > 0) { 
        childrenCount--; 
        childrenCountDisplay.textContent = childrenCount; 
    }
});