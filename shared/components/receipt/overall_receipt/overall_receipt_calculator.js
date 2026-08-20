// shared receipt HTML builder — ginagamit ng viewTouristDetails at viewHistoryReceipt
// Nilagyan ko ng multiplier (adults + children) para macalculate kunh magkano per destination.
function buildDestinationsHTML(destinationsString, adults = 0, children = 0, isPackage = false, fallback = 'No Custom Attractions Selected') {
    const raw = destinationsString || fallback;
    const pax = (parseInt(adults) || 0) + (parseInt(children) || 0);
    const multiplier = pax > 0 ? pax : 1;

    return raw.split(',').map(dest => {
        const trimmed = dest.trim();
        if (trimmed === fallback || trimmed === 'No Custom Attractions Selected' || trimmed === '') {
            return `<span class="rcpt-font-condensed">${fallback}</span>`;
        }
        
        const parts = trimmed.split('|');
        const name = parts[0] ? parts[0].trim() : '';
        const baseFee = parts[1] ? parseFloat(parts[1]) : 0;
        const totalFee = baseFee * multiplier;

        if (totalFee > 0 && !isPackage) {
            return `<span class="rcpt-font-condensed">${name}&nbsp;&nbsp;<span class="rcpt-green-sm">₱${totalFee.toLocaleString('en-PH')}</span></span>`;
        }
        
        return `<span class="rcpt-font-condensed">${name}</span>`;
    }).join('');
}

// Ito yung pinaka calculator, inaalam din here kung package ba or hindi
function calculateTotalFee(destinationsString, packagePrice, adults, children, vehiclePrice, isPackage, numberOfVehicles) {
    let vPrice = parseFloat(vehiclePrice) || 0;
    let pPrice = parseFloat(packagePrice) || 0;
    let pax = (parseInt(adults) || 0) + (parseInt(children) || 0);
    const multiplier = pax > 0 ? pax : 1;
    let vehicles = parseInt(numberOfVehicles) || 0;
    const vMultiplier = vehicles > 0 ? vehicles : 1;
    
    let baseTotal = (vPrice * vMultiplier); 

    if (isPackage) {
        baseTotal += (pPrice * multiplier); 
    } else {
        if (destinationsString && destinationsString.trim() !== '') {
            destinationsString.split(',').forEach(dest => {
                const parts = dest.trim().split('|');
                const fee = parts[1] ? parseFloat(parts[1]) : 0;
                if (fee > 0) {
                    baseTotal += (fee * multiplier); 
                }
            });
        }
    }

    const minGrandTotal = baseTotal + 1000;
    const maxGrandTotal = baseTotal + 1500;

    return {
        baseStr: baseTotal.toLocaleString('en-PH'),
        minGrandStr: minGrandTotal.toLocaleString('en-PH'),
        maxGrandStr: maxGrandTotal.toLocaleString('en-PH')
    };
}

function closeReceipt() {
    const modalOverlay = document.getElementById('tourist-receipt-overlay');
    if (modalOverlay) modalOverlay.style.display = 'none';
}

// ito yung kinocall ng viewTouristDetails at viewHistoryReceipt
function openReceiptModal(html) {
    const modalBody = document.getElementById('tourist-receipt-content');
    if (modalBody) modalBody.innerHTML = html;
    const overlay = document.getElementById('tourist-receipt-overlay');
    if (overlay) overlay.style.display = 'flex';
}