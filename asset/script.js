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