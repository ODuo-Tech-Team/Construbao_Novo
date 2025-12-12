/**
 * CONSTRUBÃO - Main JavaScript
 * Inicialização e funcionalidades globais
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar animações de scroll
    initScrollAnimations();

    // Smooth scroll para âncoras
    initSmoothScroll();
});

/**
 * Inicializa animações baseadas em scroll
 * Usa Intersection Observer para performance
 */
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('[data-animate]');

    if (animatedElements.length === 0) return;

    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -50px 0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
                // Opcional: parar de observar após animar
                // observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    animatedElements.forEach(el => {
        observer.observe(el);
    });
}

/**
 * Smooth scroll para links âncora
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');

            // Ignorar se for apenas "#"
            if (href === '#') return;

            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/**
 * Helper: Debounce function
 */
function debounce(func, wait = 100) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Helper: Throttle function
 */
function throttle(func, limit = 100) {
    let inThrottle;
    return function executedFunction(...args) {
        if (!inThrottle) {
            func(...args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

/**
 * Helper: Format currency
 */
function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}

/**
 * Helper: Format date
 */
function formatDate(dateString) {
    const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('pt-BR', options);
}

/**
 * Helper: Slugify string
 */
function slugify(text) {
    return text
        .toString()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/--+/g, '-')
        .trim();
}

/**
 * Helper: Truncate text
 */
function truncateText(text, maxLength = 100) {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength).trim() + '...';
}

/**
 * Helper: Copy to clipboard
 */
async function copyToClipboard(text) {
    try {
        await navigator.clipboard.writeText(text);
        return true;
    } catch (err) {
        console.error('Failed to copy:', err);
        return false;
    }
}

/**
 * Helper: Show toast notification
 */
function showToast(message, type = 'info', duration = 3000) {
    // Remover toast existente
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }

    // Criar novo toast
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" aria-label="Fechar">&times;</button>
    `;

    // Adicionar estilos inline (ou usar CSS)
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 12px 24px;
        background: ${type === 'success' ? '#31A24C' : type === 'error' ? '#E74C3C' : '#0A2A3F'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 9999;
        animation: slideUp 0.3s ease-out;
    `;

    document.body.appendChild(toast);

    // Auto-remove após duração
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease-out forwards';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

/**
 * Lazy load images
 */
function initLazyLoading() {
    const lazyImages = document.querySelectorAll('img[data-src]');

    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });

        lazyImages.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback para navegadores antigos
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
        });
    }
}

// Exportar funções para uso global
window.ConstrubaoUtils = {
    debounce,
    throttle,
    formatCurrency,
    formatDate,
    slugify,
    truncateText,
    copyToClipboard,
    showToast,
    initLazyLoading
};
