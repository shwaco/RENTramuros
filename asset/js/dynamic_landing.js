import { getPopularAttractions, getRecommendedAttractions, getPackages } from "../../services/api.js";

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
                <a href="#" rel="noopener noreferrer">
                    <img src="asset/img/${sliders.main_img}" alt="${sliders.attraction_name} Image">
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

function packageSlider(packageData, packageList) {
    packageData.forEach(packages => {

        const cardHTML = `<li>
                        <a href="." rel="noopener noreferrer"><div class="package one">

                            <div class="image"><img src="asset/img/${packages.image_file}" alt="${packages.package_name} Image" width="auto" height="150"></div>

                            <ul>
                                <li><div class="number"><span>${packages.package_name}</span></div></li>

                                <li><div class="attractions"><span>${packages.description}</span></div></li>

                                <li><div class="price"><span>₱${packages.price}</span></div></li>
                            </ul>
                        </div></a>
                    </li>`;

                    packageList.insertAdjacentHTML('beforeend',cardHTML);
    })
}

async function buildSlider() {
    const popAttractions = await getPopularAttractions();
    const popAttractionsList = document.getElementById('pop-attractions-list');

    const recoAttractions = await getRecommendedAttractions();
    const recoAttractionsList = document.getElementById('reco-attractions-list');

    const packageMoData = await getPackages();
    const packageList = document.getElementById('package_list');

    populateSliders(popAttractions, popAttractionsList);
    populateSliders(recoAttractions, recoAttractionsList);
    packageSlider(packageMoData, packageList);
}

document.addEventListener('DOMContentLoaded', buildSlider);