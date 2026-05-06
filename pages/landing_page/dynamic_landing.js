import { getPopularAttractions, getRecommendedAttractions, getPackages, getUpcomingEvents } from "../../services/api.js";

// Image slider 
const allSliders = document.querySelectorAll('.slider');


allSliders.forEach(slider => {

const track =  slider.querySelector('ul');
const prevBtn = slider.querySelector('.slide-btn.one');
const nextBtn = slider.querySelector('.slide-btn.two');


function getScrollAmount() {
    const itemWidth = track.querySelector('li')?.clientWidth || 0;
    const gap = 16;
    return itemWidth + gap;
}

nextBtn.addEventListener('click', () => {
    track.scrollBy({ left: getScrollAmount(), behavior: 'smooth' })
    });

prevBtn.addEventListener('click', () => {
    track.scrollBy({ left: -getScrollAmount(), behavior: 'smooth'})
})

    track.addEventListener('scroll', () => {updateButtonVisibility(track, prevBtn, nextBtn);
    });

    updateButtonVisibility(track, prevBtn, nextBtn);

});

function updateButtonVisibility (track, prevBtn, nextBtn) {
    if (track.scrollLeft <= 0   ) {
        prevBtn.style.display = 'none';
    } else {
        prevBtn.style.display = 'flex';
    }

    let maxScrollableWidth = track.scrollWidth - track.clientWidth;

    if (track.scrollLeft > maxScrollableWidth - 3) {
        nextBtn.style.display = 'none';
    } else {
        nextBtn.style.display = 'flex';
    }
}

// retrieve image sliders

function populateSliders(slidersData, slidersList) {
    slidersData.forEach(sliders => {
        
        

        const cardHTML = `
            <li>
                <a href="../overview_page/overview.php?type=attraction&id=${sliders.attraction_id}" rel="noopener noreferrer">
                    <img src="../../asset/img/${sliders.main_img}" alt="${sliders.attraction_name} Image">
                    <p>${sliders.attraction_name}</p>
                </a>
            </li>
        `;
        
        slidersList.insertAdjacentHTML('beforeend', cardHTML);
    }); 

    const sliderContainer = slidersList.closest('.slider');
    const prevBtn = sliderContainer.querySelector('.slide-btn.one');
    const nextBtn = sliderContainer.querySelector('.slide-btn.two');

    updateButtonVisibility(slidersList, prevBtn, nextBtn);
}

// retrieve packages

function packageSlider(packageData, packagesList) {
    packageData.forEach(packages => {

        const cardHTML = `<li>
                        <a href="../overview_page/overview.php?type=package&id=${packages.package_id}" rel="noopener noreferrer"><div class="package one">

                            <div class="image"><img src="../../asset/img/packages/${packages.image_file}" alt="${packages.package_name} Image" width="auto" height="150"></div>

                            <ul>
                                <li><div class="number"><span>${packages.package_name}</span></div></li>

                                <li><div class="attractions"><span>${packages.description}</span></div></li>

                                <li><div class="price"><span>₱${packages.price}</span></div></li>
                            </ul>
                        </div></a>
                    </li>`;

                    packagesList.insertAdjacentHTML('beforeend',cardHTML);
    })
}

// retrieve upcoming events

function upcomingEventsSlider(eventsData, eventsList) {
    eventsData.forEach(events => {

        const cardHTML = `<li><div class="event_container">
                                <div class="image"><img src="../../asset/img/upcoming_events/${events.image_file}" alt="${events.event_name} Image"></div>

                                <div class="details_container">
                                    <div class="schedule_container">
                                        <div class="frequency">${events.event_date}</div>
                                        <div class="time">${events.event_time}</div>
                                    </div>
                                    <div class="name">${events.event_name}</div>

                                    <div class="loc_wrapper">
                                        <img src="../../asset/img/location_icon.svg" alt="location_icon_image">

                                        <div class="loc">${events.location}</div>
                                    </div>
                                </div>
                            </div>
                        </li>`;

                    eventsList.insertAdjacentHTML('beforeend',cardHTML);
    })

    const sliderContainer = eventsList.closest('.slider');
    const prevBtn = sliderContainer.querySelector('.slide-btn.one');
    const nextBtn = sliderContainer.querySelector('.slide-btn.two');

    updateButtonVisibility(eventsList, prevBtn, nextBtn);

}

async function buildSlider() {
    const popAttractions = await getPopularAttractions();
    const popAttractionsList = document.getElementById('pop-attractions-list');

    const recoAttractions = await getRecommendedAttractions();
    const recoAttractionsList = document.getElementById('reco-attractions-list');

    const packageMoData = await getPackages();
    const packageList = document.getElementById('package_list');

    const upcomingEvents = await getUpcomingEvents();
    const upcomingEventsList = document.getElementById('upcoming_events_list');

    populateSliders(popAttractions, popAttractionsList);
    populateSliders(recoAttractions, recoAttractionsList);
    packageSlider(packageMoData, packageList);
    upcomingEventsSlider(upcomingEvents, upcomingEventsList);
}

document.addEventListener('DOMContentLoaded', buildSlider);

let historyTours = []; // Start with an empty array

async function loadHistory() {
    try {
        // IMPORTANT: Ensure this path points to your TOURIST history API, not the guide history
        const response = await fetch('../../backend/api/ui/tourist/get_tourist_history.php');
        const data = await response.json();
        const container = document.getElementById('historyContainer');

        if (data.success && data.history && data.history.length > 0) {
            historyTours = data.history;

            container.innerHTML = historyTours.map((tour, index) => {
                const rawDate = tour.booking_date ? (tour.booking_time ? `${tour.booking_date} ${tour.booking_time}` : tour.booking_date) : null;
                const dateObj = rawDate ? new Date(rawDate.replace(/-/g, '/')) : new Date();
                const formattedDate = dateObj.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: '2-digit' });
                const timeString = dateObj.toLocaleTimeString('en-US', { hour: 'numeric', hour12: true }).replace(' ', '');

                const tourTitle = tour.package_name ? tour.package_name : 'Custom Tour';
                let statusText = tour.status || 'Pending'; 
                let statusClass = '';

                if (statusText === 'Completed') statusClass = 'status-completed';
                else if (statusText === 'Cancelled') statusClass = 'status-cancelled';
                else if (statusText === 'Accepted') statusClass = 'status-accepted';
                else statusClass = 'status-pending';

                return `
                    <div class="booking-card" onclick="viewHistoryReceipt(${index})">
                        <div class="bc-left"><span class="bc-id">${tour.unique_id}</span></div>
                        <div class="bc-middle">
                            <span class="bc-date">${formattedDate} ${timeString}</span>
                            <span class="bc-title">${tourTitle}</span>
                        </div>
                        <div class="bc-right"><span class="bc-status ${statusClass}">${statusText}</span></div>
                    </div>`;
            }).join('');
        } else {
            // Shows this if the user is logged in but hasn't booked anything yet
            container.innerHTML = `
                <div style="text-align: center; padding: 4rem 1rem; color: #6b7280; font-size: 1.1rem; font-style: italic;">
                    No bookings yet. Head over to the Tours page to start your journey!
                </div>
            `;
        }
    } catch (e) {
        console.error("Failed to load history:", e);
        // If not logged in, or API fails, show a generic message
        const container = document.getElementById('historyContainer');
        if (container) {
            container.innerHTML = `
                <div style="text-align: center; padding: 4rem 1rem; color: #6b7280; font-size: 1.1rem; font-style: italic;">
                    Please log in to view your bookings.
                </div>
            `;
        }
    }
}

// 2. Receipt View Click Handler[cite: 4]
window.viewHistoryReceipt = function(index) {
    const tour = historyTours[index];
    if (!tour) return;

    const rawDate = tour.booking_date ? (tour.booking_time ? `${tour.booking_date} ${tour.booking_time}` : tour.booking_date) : null;
    const dateObj = rawDate ? new Date(rawDate.replace(/-/g, '/')) : new Date();
    const formattedDate = dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) + ' ; ' + dateObj.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit' });

    const isPackage = tour.package_name ? true : false;
    const destinationsHTML = buildDestinationsHTML(tour.destinations, tour.adults_and_seniors, tour.children, isPackage, 'No destinations listed');

    openReceiptModal(buildReceiptHTML({
        id: tour.unique_id, formattedDate, adults_and_seniors: tour.adults_and_seniors, children: tour.children, infants: tour.infants, package_name: tour.package_name, package_price_val: tour.package_price, vehicle_price_val: tour.vehicle_price, destinations: tour.destinations, destinationsHTML, vehicle_type: tour.vehicle_type, number_of_vehicle: tour.number_of_vehicle, first_name: tour.first_name, last_name: tour.last_name, email_address: tour.email_address, phone_number: tour.phone_number, actionArea: ''
    }));
};

// 3. Helper Functions (Builder, Calculator, Modals)[cite: 9, 10

// Make sure your script calls loadHistory() on load!
document.addEventListener('DOMContentLoaded', loadHistory);

// ====================== PASTE THE REST OF [SOURCE: 10] BELOW THIS LINE ======================
// (buildDestinationsHTML, calculateTotalFee, closeReceipt, openReceiptModal)

function buildReceiptHTML({ id, formattedDate, adults_and_seniors, children, infants, package_name, package_price_val = 0, vehicle_price_val = 0, destinations, destinationsHTML, vehicle_type, number_of_vehicle, first_name, last_name, email_address, phone_number, actionArea = '' }) {
    
    // Check if it's a real package[cite: 19]
    const isPackage = package_name && package_name !== 'No Package' && package_name !== 'Custom Tour'; 
    
    const packagePrice = parseFloat(package_price_val) || 0;
    const vehiclePrice = parseFloat(vehicle_price_val) || 0;
    const vehicleCount = parseInt(number_of_vehicle) || 0;
    
    const pax = (parseInt(adults_and_seniors) || 0) + (parseInt(children) || 0);
    const multiplier = pax > 0 ? pax : 1;
    const totalPackageCost = packagePrice * multiplier;

    let packageDisplayString = (package_name === 'Custom Tour' || !package_name) ? 'No Package' : package_name;
    
    if (isPackage && totalPackageCost > 0) {
        packageDisplayString += `&nbsp;&nbsp;<span class="rcpt-green-text">₱${totalPackageCost.toLocaleString('en-PH')}</span>`;
    }

    const feeData = calculateTotalFee(destinations, packagePrice, adults_and_seniors, children, vehiclePrice, isPackage, vehicleCount);
    const updatedDestinationsHTML = buildDestinationsHTML(destinations, adults_and_seniors, children, isPackage, 'No destinations listed');
    
    let vehicleDisplayString = `${vehicle_type || 'NONE'}`;
    if (vehiclePrice > 0) {
        const totalVehicleCost = vehiclePrice * (vehicleCount > 0 ? vehicleCount : 1);
        vehicleDisplayString += `&nbsp;&nbsp;<span class="rcpt-green-sm">₱${totalVehicleCost.toLocaleString('en-PH')}</span>`;
    }

    let templateHTML = document.getElementById('receipt-modal-template').innerHTML;

    return templateHTML
        .replace('{{id}}', id)
        .replace('{{formattedDate}}', formattedDate)
        .replace('{{adults_and_seniors}}', adults_and_seniors || 0)
        .replace('{{children}}', children || 0)
        .replace('{{infants}}', infants || 0)
        .replace('{{packageDisplayString}}', packageDisplayString)
        .replace('{{destinationsHTML}}', updatedDestinationsHTML)
        .replace('{{vehicleDisplayString}}', vehicleDisplayString)
        .replace('{{number_of_vehicle}}', number_of_vehicle || 0)
        .replace('{{first_name}}', first_name || '')
        .replace('{{last_name}}', last_name || '')
        .replace('{{email_address}}', email_address || ' ')
        .replace('{{phone_number}}', phone_number || ' ')
        .replace('{{baseStr}}', feeData.baseStr)
        .replace('{{minGrandStr}}', feeData.minGrandStr)
        .replace('{{maxGrandStr}}', feeData.maxGrandStr)
        .replace('{{actionArea}}', actionArea);
}

// shared receipt HTML builder — ginagamit ng viewTouristDetails at viewHistoryReceipt
// Nilagyan ko ng multiplier (adults + children) para macalculate kunh magkano per destination.
function buildDestinationsHTML(destinationsString, adults = 0, children = 0, isPackage = false, fallback = 'No Custom Attractions Selected') {
    const raw = destinationsString || fallback;
    const pax = (parseInt(adults) || 0) + (parseInt(children) || 0);
    const multiplier = pax > 0 ? pax : 1;

    return raw.split(',').map(dest => {
        const trimmed = dest.trim();
        if (trimmed === fallback || trimmed === 'No Custom Attractions Selected' || trimmed === '') {
            return `<span>${fallback}</span>`;
        }
        
        const parts = trimmed.split('|');
        const name = parts[0] ? parts[0].trim() : '';
        const baseFee = parts[1] ? parseFloat(parts[1]) : 0;
        const totalFee = baseFee * multiplier;

        if (totalFee > 0 && !isPackage) {
            return `<span>${name}&nbsp;&nbsp;<span class="rcpt-green-sm">₱${totalFee.toLocaleString('en-PH')}</span></span>`;
        }
        
        return `<span>${name}</span>`;
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

window.closeReceipt = function() {
    const modalOverlay = document.getElementById('tourist-receipt-overlay');
    if (modalOverlay) {
        modalOverlay.style.display = 'none';
    }
};

// ito yung kinocall ng viewTouristDetails at viewHistoryReceipt
function openReceiptModal(html) {
    const modalBody = document.getElementById('tourist-receipt-content');
    if (modalBody) modalBody.innerHTML = html;
    const overlay = document.getElementById('tourist-receipt-overlay');
    if (overlay) overlay.style.display = 'flex';
}