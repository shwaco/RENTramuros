<template id="receipt-modal-template">
    <div class="rcpt-header-container">
        <div class="rcpt-id-badge">{{id}}</div>
        <button onclick="closeReceipt()" class="rcpt-close-btn">&times;</button>
    </div>

    <div class="rcpt-date-text">{{formattedDate}}</div>

    <div class="rcpt-section-title">TOURIST</div>

    <div class="rcpt-grid-3">
        <span class="rcpt-label">ADULTS & SENIORS</span>
        <span class="rcpt-subtext">(18 years old and above)</span>
        <span class="rcpt-value">{{adults_and_seniors}}</span>
    </div>

    <div class="rcpt-grid-3">
        <span class="rcpt-label">CHILDREN</span>
        <span class="rcpt-subtext">(2 to 17 years old)</span>
        <span class="rcpt-value">{{children}}</span>
    </div>

    <div class="rcpt-grid-3 last">
        <span class="rcpt-label">INFANTS</span>
        <span class="rcpt-subtext">(under 2 years old)</span>
        <span class="rcpt-value">{{infants}}</span>
    </div>

    <div class="rcpt-flex-between">
        <span class="rcpt-bold-label">PACKAGE</span>
        <span class="rcpt-font-condensed">{{packageDisplayString}}</span>
    </div>

    <hr class="rcpt-divider-dashed">

    <div class="rcpt-section-title">ITINERARY</div>

    <div class="rcpt-itinerary-grid">
        {{destinationsHTML}}
    </div>

    <div class="rcpt-grid-3">
        <span class="rcpt-bold-label">VEHICLE</span>
        <span class="rcpt-uppercase rcpt-center">{{vehicleDisplayString}}</span>
        <span class="rcpt-font-condensed rcpt-bold-value">{{number_of_vehicle}}</span>
    </div>

    <hr class="rcpt-divider-dashed">

    <div class="rcpt-section-title">CONTACT INFORMATION</div>

    <div class="rcpt-flex-between-sm">
        <span class="rcpt-font-condensed">FULL NAME:</span>
        <span class="rcpt-uppercase">{{first_name}} {{last_name}}</span>
    </div>
    <div class="rcpt-flex-between-sm">
        <span class="rcpt-font-condensed">EMAIL ADDRESS:</span>
        <span class="rcpt-font-condensed">{{email_address}}</span>
    </div>
    <div class="rcpt-flex-between-md">
        <span class="rcpt-font-condensed">PHONE NUMBER:</span>
        <span class="rcpt-font-condensed">{{phone_number}}</span>
    </div>

    <hr class="rcpt-divider-solid">

    <div class="rcpt-totals-container">
        <div class="rcpt-totals-grid">
            <span class="rcpt-total-label">TOTAL FEE:</span>
            <span class="rcpt-total-val">₱{{baseStr}}</span>
            
            <span class="rcpt-total-label">TOUR GUIDE FEE:</span>
            <span class="rcpt-total-val">₱1,000 - ₱1,500</span>
            
            <span class="rcpt-grand-label">GRAND TOTAL:</span>
            <span class="rcpt-grand-val">₱{{minGrandStr}} - ₱{{maxGrandStr}}</span>
        </div>
        {{actionArea}}
    </div>
</template>