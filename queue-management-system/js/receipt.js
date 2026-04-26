// shared receipt HTML builder for receipt ng tourist details and sa hisotry
// para hindi mag-duplicate ng template ang dalawang functions
function buildDestinationsHTML(destinationsString, fallback = 'No destinations listed') {
    const raw = destinationsString || fallback;
    return raw.split(',').map(dest => {
        const trimmed = dest.trim();
        if (trimmed === 'No destinations listed' || trimmed === 'No itineraries listed') {
            return `<span>${trimmed}</span>`;
        }
        // para mauna yung name then entrance fee
        const parts = trimmed.split('|');
        const name = parts[0] ? parts[0].trim() : '';
        const fee = parts[1] ? parseInt(parts[1], 10) : 0;

        if (fee > 0) {
            return `<span>${name}&nbsp;&nbsp;<span style="color: #109620; font-weight: 600; font-style: italic; font-size: 0.8rem;">₱${fee}</span></span>`;
        }
        return `<span>${name}</span>`;
    }).join('');
}

function buildReceiptHTML({ id, formattedDate, adult_count, children_count, infant_count, package_name, destinationsHTML, service_type, vehicle_count, first_name, last_name, email, phone_number, actionArea = '', feeDisplay = '₱4,500-5000' }) {
    return `
        <div style="display: flex; justify-content: space-between; align-items: center; margin: 0 -2rem; padding: 1.5rem 2rem 1rem 2rem; border-bottom: 1px solid #e5e7eb;">
            <div style="background-color: #000000; color: #ffffff; font-family: 'Roboto Condensed', sans-serif; font-size: 1.4rem; font-weight: 700; padding: 0.4rem 1.2rem; border-radius: 4px; display: inline-flex; justify-content: center; align-items: center; line-height: 1;">
                ${id}
            </div>
            <button 
                onclick="closeReceipt()" 
                onmouseover="this.style.color='#FF0000'" 
                onmouseout="this.style.color='#9ca3af'" 
                style="background:none; border:none; font-size:2rem; cursor:pointer; color:#9ca3af; font-style: normal; line-height: 1; padding: 0; transition: color 0.2s;"
            >
                &times;
            </button>
        </div>

        <div style="text-align: right; font-size: 0.8rem; color: #000000; margin-top: 1.5rem; margin-bottom: 2rem; font-family: 'Roboto Condensed', sans-serif; font-weight: 400;">
            ${formattedDate}
        </div>

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; color:#000; font-family: 'Roboto Condensed', sans-serif;">TOURIST</div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem;">
            <span style="padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ADULTS & SENIORS</span>
            <span style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(18 years old and above)</span>
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;">${adult_count || 0}</span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:0.6rem; font-size:0.85rem;">
            <span style="padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CHILDREN</span>
            <span style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(2 to 17 years old)</span>
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;">${children_count || 0}</span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; margin-bottom:1.5rem; font-size:0.85rem;">
            <span style="padding-left: 0.5rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">INFANTS</span>
            <span style="font-weight: 300; font-style:italic; font-size:0.8rem; text-align: center; font-family: 'Roboto Condensed', sans-serif; color: #000000;">(under 2 years old)</span>
            <span style="text-align: right; font-family: 'Roboto Condensed', sans-serif; color: #000000;">${infant_count || 0}</span>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.85rem;">
            <span style="font-weight:700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">PACKAGE</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${package_name || 'Sacred Route'}</span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">ITINERARY</div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; font-size: 0.8rem; font-family: 'Roboto Condensed', sans-serif; color: #000000; margin-bottom: 1.5rem;">
            ${destinationsHTML}
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; font-size: 0.85rem;">
            <span style="font-weight:700; font-family: 'Roboto Condensed', sans-serif; color: #000000;">VEHICLE</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000; text-transform: uppercase; text-align: center;">${service_type || 'NONE'}</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000; text-align: right; font-weight: bold;">${vehicle_count || 0}</span>
        </div>

        <hr style="border: 0; border-top: 1px dashed #d1d5db; margin: 1.5rem 0;">

        <div style="font-weight:700; font-size:0.9rem; margin-bottom:1rem; font-family: 'Roboto Condensed', sans-serif; color: #000000;">CONTACT INFORMATION</div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">FULL NAME:</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${first_name || ''} ${last_name || ''}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">EMAIL ADDRESS:</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${email || 'N/A'}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.85rem;">
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">PHONE NUMBER:</span>
            <span style="font-family: 'Roboto Condensed', sans-serif; color: #000000;">${phone_number || 'N/A'}</span>
        </div>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 1.5rem 0;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                <span style="font-weight: 300; font-family: 'Roboto Condensed', sans-serif; color: #000000; font-size: 0.9rem;">TOTAL FEE:</span>
                <span style="font-weight: 400; font-family: 'Roboto Condensed', sans-serif; color: #109620; font-size: 0.9rem; font-style: italic;">₱4,500-5000</span>
            </div>
            ${actionArea}
        </div>
    `;
}

function closeReceipt() {
    const modalOverlay = document.getElementById('tourist-receipt-overlay');
    if (modalOverlay) modalOverlay.style.display = 'none';
}

// ipinopopulate yung receipt modal ng HTML para mapakita sa user
function openReceiptModal(html) {
    const modalBody = document.getElementById('tourist-receipt-content');
    if (modalBody) modalBody.innerHTML = html;
    const overlay = document.getElementById('tourist-receipt-overlay');
    if (overlay) overlay.style.display = 'flex';
}