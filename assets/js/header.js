/**
 * CONSTRUBÃO - Header JavaScript
 * Menu mobile toggle e funcionalidades do header
 */

document.addEventListener('DOMContentLoaded', function() {
    initMobileMenu();
    initHeaderScroll();
});

/**
 * Inicializa o menu mobile
 */
function initMobileMenu() {
    const toggle = document.getElementById('mobileMenuToggle');
    const menu = document.getElementById('mobileMenu');

    if (!toggle || !menu) return;

    // Toggle menu ao clicar no botão
    toggle.addEventListener('click', function() {
        toggle.classList.toggle('active');
        menu.classList.toggle('active');

        // Accessibility
        const isExpanded = menu.classList.contains('active');
        toggle.setAttribute('aria-expanded', isExpanded);

        // Prevenir scroll do body quando menu está aberto
        document.body.style.overflow = isExpanded ? 'hidden' : '';
    });

    // Fechar menu ao clicar em um link
    const menuLinks = menu.querySelectorAll('a');
    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            toggle.classList.remove('active');
            menu.classList.remove('active');
            document.body.style.overflow = '';
        });
    });

    // Fechar menu ao clicar fora
    document.addEventListener('click', function(e) {
        if (!menu.contains(e.target) && !toggle.contains(e.target)) {
            if (menu.classList.contains('active')) {
                toggle.classList.remove('active');
                menu.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });

    // Fechar menu com tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && menu.classList.contains('active')) {
            toggle.classList.remove('active');
            menu.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Fechar menu ao redimensionar para desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024 && menu.classList.contains('active')) {
            toggle.classList.remove('active');
            menu.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
}

/**
 * Header scroll effects
 * Adiciona sombra e reduz tamanho ao rolar
 */
function initHeaderScroll() {
    const header = document.querySelector('.header');
    if (!header) return;

    let lastScrollY = window.scrollY;
    let ticking = false;

    function updateHeader() {
        const scrollY = window.scrollY;

        // Adicionar classe quando rolar
        if (scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        // Opcional: esconder/mostrar header ao rolar
        // if (scrollY > lastScrollY && scrollY > 200) {
        //     header.classList.add('hidden');
        // } else {
        //     header.classList.remove('hidden');
        // }

        lastScrollY = scrollY;
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(updateHeader);
            ticking = true;
        }
    }, { passive: true });
}

// CSS adicional para estados do header (pode ser movido para CSS)
const headerStyles = document.createElement('style');
headerStyles.textContent = `
    .header {
        transition: box-shadow 0.3s ease, padding 0.3s ease;
    }

    .header.scrolled {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .header.scrolled .header-logo img {
        height: 60px;
    }

    @media (min-width: 1024px) {
        .header.scrolled .header-logo img {
            height: 70px;
        }
    }

    .header.hidden {
        transform: translateY(-100%);
    }
`;
document.head.appendChild(headerStyles);
