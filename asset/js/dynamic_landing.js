import { getPopularAttractions, getRecommendedAttractions } from "../../services/api.js";

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

// retrieve popular and recommended attractions

function populateSliders(attractionsData, attractionsList) {
    attractionsData.forEach(attraction => {
        
        
        const cardHTML = `
            <li>
                <a href="#" rel="noopener noreferrer">
                    <img src="asset/img/${attraction.main_img}" alt="${attraction.attraction_name} Image">
                    <p>${attraction.attraction_name}</p>
                </a>
            </li>
        `;

        // const cardHTML = `<li>
        //                 <a href="." rel="noopener noreferrer"><div class="package one">

        //                     <div class="image"><img src="asset/img/la_costa.jpg" alt="package_picture" width="auto" height="150"></div>

        //                     <ul>
        //                         <li><div class="number"><span>Package 1</span></div></li>

        //                         <li><div class="attractions"><span>Casa la cote + Puerto berde + Juju on the beat + No merk + No dihh + no bruhhhhhhhhhhhhhhhhh + No shi + nosssssssssssssssssssssssssssssssssssssssssssssssssssss  </span></div></li>

        //                         <li><div class="price"><span>₱67,6767</span></div></li>
        //                     </ul>
        //                 </div></a>
        //             </li>`

        
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

    // const package = await getPackages();
    // const packageList = document.getElementById('package_list');

    populateSliders(popAttractions, popAttractionsList);
    populateSliders(recoAttractions, recoAttractionsList);
    // populateSliders(package, packageList);
}

document.addEventListener('DOMContentLoaded', buildSlider);


// retrieve packages

