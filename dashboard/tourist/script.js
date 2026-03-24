// Sidebar
function showSidebar() {
    const sidebar = document.querySelector('.sidebar')
    sidebar.style.display = 'flex'
}

function hideSidebar() {
    const sidebar = document.querySelector('.sidebar')
    sidebar.style.display = 'none'
}

// Image slider 1
const track =  document.querySelector('.slider1 ul');
const prevBtn = document.getElementById('prev-btn1');
const nextBtn = document.getElementById('next-btn1');

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
    if (track.scrollLeft <= 5) {
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