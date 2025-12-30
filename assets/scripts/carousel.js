//=== CAROUSEL HORIZONTAL MODERNE ===
const carouselSlides = document.querySelector('.carousel-slides');
const prevBtn = document.querySelector('.carousel-btn--prev');
const nextBtn = document.querySelector('.carousel-btn--next');

let currentIndex = 0;
let isAnimating = false;

function slideCarousel(direction) {
    if (isAnimating) return;
    isAnimating = true;
    
    const slides = document.querySelectorAll('.carousel-slide');
    const totalSlides = slides.length;
    
    if (direction === 'next') {
        currentIndex = (currentIndex + 1) % totalSlides;
    } else {
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
    }
    
    const offset = -currentIndex * 100;
    carouselSlides.style.transform = `translateX(${offset}%)`;
    
    setTimeout(() => {
        isAnimating = false;
    }, 500);
}

nextBtn?.addEventListener('click', () => slideCarousel('next'));
prevBtn?.addEventListener('click', () => slideCarousel('prev'));

// Support clavier
document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight') slideCarousel('next');
    if (e.key === 'ArrowLeft') slideCarousel('prev');
});

