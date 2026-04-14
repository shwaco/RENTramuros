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

// retrieve attractions
async function loadPopularAttractions() {
    try {
        const response = await fetch('/RENTramuros/backend/attractions/retrieve_attractions.php'); 
        
        const result = await response.json();

        if (result.status === "success") {
            
            const attractionsArray = result.data; 
            
            const sliderList = document.getElementById('popular-attractions-list');
            
            attractionsArray.forEach(attraction => {
                
                const cardHTML = `
                    <li>
                        <a href="#" rel="noopener noreferrer">
                            <img src="../../asset/img/${attraction.image_file}" alt="${attraction.attraction_name} Image">
                            <p>${attraction.attraction_name}</p>
                        </a>
                    </li>
                `;
                
                sliderList.insertAdjacentHTML('beforeend', cardHTML);
            }); 

            const sliderContainer = sliderList.closest('.slider');
            const prevBtn = sliderContainer.querySelector('.slide-btn.one');
            const nextBtn = sliderContainer.querySelector('.slide-btn.two');
            
            updateButtonVisibility(sliderList, prevBtn, nextBtn);
            
        } else {
            console.error("Backend Error:", result.message); 
        }

    } catch (error) {
        console.error("Network Failure:", error);
    }

}

document.addEventListener('DOMContentLoaded', loadPopularAttractions);

