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
    const itemWidth = track.querySelector('li').clientWidth;
    const gap = 16;
    return itemWidth + gap;
}

nextBtn.addEventListener('click', () => {
    track.scrollBy({ left: getScrollAmount(), behavior: 'smooth' })
    });

prevBtn.addEventListener('click', () => {
    track.scrollBy({ left: -getScrollAmount(), behavior: 'smooth'})
})

function updateButtonVisibility () {
    if (track.scrollLeft <= 10) {
        prevBtn.style.display = 'none';
    } else {
        prevBtn.style.display = 'flex';
    }
    if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 5) {
        nextBtn.style.display = 'none';
    } else {
        nextBtn.style.display = 'flex';
    }
}

    track.addEventListener('scroll', updateButtonVisibility);

updateButtonVisibility();

});

// retrieve attractions
async function loadPopularAttractions() {
    try {
        const response = await fetch('../../inclusions/attractions/retrieve_attractions.php'); 
        
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
            
        } else {
            console.error("Backend Error:", result.message); 
        }

    } catch (error) {
        console.error("Network Failure:", error);
    }
}

document.addEventListener('DOMContentLoaded', loadPopularAttractions);