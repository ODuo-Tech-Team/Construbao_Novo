/**
 * CONSTRUBÃO - Carousel JavaScript
 * Carrossel vanilla para depoimentos
 */

class Carousel {
    constructor(element, options = {}) {
        this.carousel = element;
        this.track = element.querySelector('.carousel-track');
        this.slides = Array.from(element.querySelectorAll('.carousel-slide'));
        this.prevBtn = element.querySelector('.carousel-prev');
        this.nextBtn = element.querySelector('.carousel-next');
        this.dotsContainer = element.querySelector('.carousel-dots');

        // Opções
        this.options = {
            autoplay: options.autoplay ?? false,
            autoplayInterval: options.autoplayInterval ?? 5000,
            loop: options.loop ?? true,
            slidesPerView: options.slidesPerView ?? 1,
            gap: options.gap ?? 24,
            ...options
        };

        this.currentIndex = 0;
        this.autoplayTimer = null;
        this.isDragging = false;
        this.startX = 0;
        this.currentX = 0;
        this.slidesPerView = this.options.slidesPerView;

        this.init();
    }

    init() {
        if (!this.track || this.slides.length === 0) return;

        this.updateSlidesPerView();
        this.setupSlides();
        this.setupDots();
        this.setupNavigation();
        this.setupDragEvents();
        this.updateCarousel();

        if (this.options.autoplay) {
            this.startAutoplay();
        }

        // Responsive
        window.addEventListener('resize', () => {
            this.updateSlidesPerView();
            this.setupSlides();
            this.updateCarousel();
        });
    }

    setupSlides() {
        const gap = this.options.gap;
        const totalGaps = this.slidesPerView - 1;
        const slideWidth = `calc((100% - ${totalGaps * gap}px) / ${this.slidesPerView})`;

        this.slides.forEach((slide, index) => {
            slide.style.flex = '0 0 auto';
            slide.style.width = slideWidth;
            slide.style.marginRight = index < this.slides.length - 1 ? `${gap}px` : '0';
        });

        this.track.style.display = 'flex';
        this.track.style.transition = 'transform 0.5s ease';
    }

    updateSlidesPerView() {
        const width = window.innerWidth;
        if (width < 640) {
            this.slidesPerView = 1;
        } else if (width < 1024) {
            this.slidesPerView = Math.min(2, this.options.slidesPerView);
        } else {
            this.slidesPerView = this.options.slidesPerView;
        }
    }

    setupDots() {
        if (!this.dotsContainer) return;

        this.dotsContainer.innerHTML = '';
        const totalDots = Math.ceil(this.slides.length / this.slidesPerView);

        for (let i = 0; i < totalDots; i++) {
            const dot = document.createElement('button');
            dot.className = 'carousel-dot';
            dot.setAttribute('aria-label', `Ir para slide ${i + 1}`);
            dot.addEventListener('click', () => this.goToSlide(i * this.slidesPerView));
            this.dotsContainer.appendChild(dot);
        }
    }

    setupNavigation() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prev());
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.next());
        }

        // Keyboard navigation
        this.carousel.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                this.prev();
            } else if (e.key === 'ArrowRight') {
                this.next();
            }
        });
    }

    setupDragEvents() {
        // Mouse events
        this.track.addEventListener('mousedown', (e) => this.startDrag(e));
        this.track.addEventListener('mousemove', (e) => this.drag(e));
        this.track.addEventListener('mouseup', () => this.endDrag());
        this.track.addEventListener('mouseleave', () => this.endDrag());

        // Touch events
        this.track.addEventListener('touchstart', (e) => this.startDrag(e), { passive: true });
        this.track.addEventListener('touchmove', (e) => this.drag(e), { passive: true });
        this.track.addEventListener('touchend', () => this.endDrag());
    }

    startDrag(e) {
        this.isDragging = true;
        this.startX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
        this.track.style.transition = 'none';
        this.track.style.cursor = 'grabbing';

        if (this.options.autoplay) {
            this.stopAutoplay();
        }
    }

    drag(e) {
        if (!this.isDragging) return;

        const x = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
        this.currentX = x - this.startX;

        const currentTranslate = this.getTranslateX();
        this.track.style.transform = `translateX(${currentTranslate + this.currentX}px)`;
    }

    endDrag() {
        if (!this.isDragging) return;

        this.isDragging = false;
        this.track.style.transition = 'transform 0.5s ease';
        this.track.style.cursor = 'grab';

        const threshold = 50;

        if (this.currentX < -threshold) {
            this.next();
        } else if (this.currentX > threshold) {
            this.prev();
        } else {
            this.updateCarousel();
        }

        this.currentX = 0;

        if (this.options.autoplay) {
            this.startAutoplay();
        }
    }

    getTranslateX() {
        const slideWidth = this.slides[0].offsetWidth + this.options.gap;
        return -this.currentIndex * slideWidth;
    }

    prev() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
        } else if (this.options.loop) {
            this.currentIndex = this.slides.length - this.slidesPerView;
        }
        this.updateCarousel();
    }

    next() {
        const maxIndex = this.slides.length - this.slidesPerView;
        if (this.currentIndex < maxIndex) {
            this.currentIndex++;
        } else if (this.options.loop) {
            this.currentIndex = 0;
        }
        this.updateCarousel();
    }

    goToSlide(index) {
        const maxIndex = this.slides.length - this.slidesPerView;
        this.currentIndex = Math.max(0, Math.min(index, maxIndex));
        this.updateCarousel();
    }

    updateCarousel() {
        const translateX = this.getTranslateX();
        this.track.style.transform = `translateX(${translateX}px)`;

        // Update dots
        if (this.dotsContainer) {
            const dots = this.dotsContainer.querySelectorAll('.carousel-dot');
            const activeDot = Math.floor(this.currentIndex / this.slidesPerView);
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === activeDot);
            });
        }

        // Update navigation buttons
        if (!this.options.loop) {
            if (this.prevBtn) {
                this.prevBtn.disabled = this.currentIndex === 0;
            }
            if (this.nextBtn) {
                this.nextBtn.disabled = this.currentIndex >= this.slides.length - this.slidesPerView;
            }
        }
    }

    startAutoplay() {
        this.stopAutoplay();
        this.autoplayTimer = setInterval(() => {
            this.next();
        }, this.options.autoplayInterval);
    }

    stopAutoplay() {
        if (this.autoplayTimer) {
            clearInterval(this.autoplayTimer);
            this.autoplayTimer = null;
        }
    }

    destroy() {
        this.stopAutoplay();
        // Remove event listeners if needed
    }
}

// Inicialização automática
document.addEventListener('DOMContentLoaded', function() {
    const carousels = document.querySelectorAll('[data-carousel]');

    carousels.forEach(carousel => {
        const options = {
            autoplay: carousel.dataset.autoplay === 'true',
            autoplayInterval: parseInt(carousel.dataset.interval) || 5000,
            loop: carousel.dataset.loop !== 'false',
            slidesPerView: parseInt(carousel.dataset.slides) || 1,
            gap: parseInt(carousel.dataset.gap) || 24
        };

        new Carousel(carousel, options);
    });
});

// Estilos do carrossel
const carouselStyles = document.createElement('style');
carouselStyles.textContent = `
    .carousel {
        position: relative;
        overflow: hidden;
    }

    .carousel-track {
        display: flex;
        cursor: grab;
        user-select: none;
    }

    .carousel-slide {
        flex-shrink: 0;
    }

    .carousel-prev,
    .carousel-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--color-white);
        border: none;
        box-shadow: var(--shadow-md);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        transition: all 0.3s ease;
    }

    .carousel-prev:hover,
    .carousel-next:hover {
        background: var(--color-primary);
        transform: translateY(-50%) scale(1.1);
    }

    .carousel-prev:disabled,
    .carousel-next:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .carousel-prev {
        left: 16px;
    }

    .carousel-next {
        right: 16px;
    }

    .carousel-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 24px;
    }

    .carousel-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--color-gray-300);
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .carousel-dot:hover {
        background: var(--color-gray-400);
    }

    .carousel-dot.active {
        background: var(--color-primary);
        transform: scale(1.2);
    }

    @media (max-width: 640px) {
        .carousel-prev,
        .carousel-next {
            width: 40px;
            height: 40px;
        }

        .carousel-prev {
            left: 8px;
        }

        .carousel-next {
            right: 8px;
        }
    }
`;
document.head.appendChild(carouselStyles);

// Export for use
window.Carousel = Carousel;
