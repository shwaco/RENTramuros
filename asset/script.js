import { getPopularAttractions, getRecommendedAttractions } from "../services/api.js";

// Sidebar
function showSidebar() {
    const sidebar = document.querySelector('.sidebar')
    sidebar.style.display = 'flex'
}

function hideSidebar() {
    const sidebar = document.querySelector('.sidebar')
    sidebar.style.display = 'none'
}

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
    if (track.scrollWidth > 0) {
        if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 5) {
            nextBtn.style.display = 'none';
        } else {
            nextBtn.style.display = 'flex';
        }
    }
}

// retrieve popular and recommended attractions

function populateSliders(attractionsData, attractionsList) {
    attractionsData.forEach(attraction => {
        
        const cardHTML = `
            <li>
                <a href="#" rel="noopener noreferrer">
                    <img src="../../asset/img/${attraction.image_file}" alt="${attraction.attraction_name} Image">
                    <p>${attraction.attraction_name}</p>
                </a>
            </li>
        `;
        
        attractionsList.insertAdjacentHTML('beforeend', cardHTML);
    }); 

    const sliderContainer = attractionsList.closest('.slider');
    const prevBtn = sliderContainer.querySelector('.slide-btn.one');
    const nextBtn = sliderContainer.querySelector('.slide-btn.two');

    updateButtonVisibility(attractionsList, prevBtn, nextBtn);
}

async function buildSlider() {
    const popAttractions = await getPopularAttractions();
    const popAttractionsList = document.getElementById('pop-attractions-list');

    const recoAttractions = await getRecommendedAttractions();
    const recoAttractionsList = document.getElementById('reco-attractions-list');

    populateSliders(popAttractions, popAttractionsList);
    populateSliders(recoAttractions, recoAttractionsList);
}

document.addEventListener('DOMContentLoaded', buildSlider);
