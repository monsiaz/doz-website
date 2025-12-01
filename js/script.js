// Script Doz - Coffee & Lagree

document.addEventListener('DOMContentLoaded', function() {
    
    // 0. Smooth Scroll (Lenis) - Effet "Léché"
    const lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        direction: 'vertical',
        gestureDirection: 'vertical',
        smooth: true,
        mouseMultiplier: 1,
        smoothTouch: false,
        touchMultiplier: 2,
    });

    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }

    requestAnimationFrame(raf);

    // 1. Initialisation des animations
    document.body.classList.add('js-ready');

    const animatedElements = document.querySelectorAll('.fade-in-up');

    // 2. Navbar : Gestion du Menu Plein Écran Mobile
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    const navLinks = document.querySelectorAll('.nav-link');
    const body = document.body;

    // Toggle Menu
    navbarToggler.addEventListener('click', () => {
        if (!navbarCollapse.classList.contains('show')) {
            body.style.overflow = 'hidden'; 
            lenis.stop(); // Bloque le scroll Lenis
        } else {
            body.style.overflow = ''; 
            lenis.start(); // Reprend le scroll Lenis
        }
    });

    // Fermeture au clic sur un lien
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (navbarCollapse.classList.contains('show')) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                bsCollapse.hide();
                body.style.overflow = '';
                lenis.start();
            }
        });
    });

    // 3. Navbar Change Color on Scroll
    const navbar = document.querySelector('.navbar-doz');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // 4. Observer pour Animations au scroll
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target); 
            }
        });
    }, observerOptions);

    animatedElements.forEach(el => {
        observer.observe(el);
    });

    // 5. Parallaxe simple sur les images (Effet "Moderne")
    const parallaxImages = document.querySelectorAll('.parallax-img');
    window.addEventListener('scroll', () => {
        const scrolled = window.scrollY;
        parallaxImages.forEach(img => {
            const speed = img.dataset.speed || 0.1;
            const yPos = -(scrolled * speed);
            img.style.transform = `translateY(${yPos}px)`;
        });
    });

});

    // 6. Custom Cursor
    const cursor = document.createElement('div');
    cursor.classList.add('custom-cursor');
    document.body.appendChild(cursor);

    document.addEventListener('mousemove', (e) => {
        cursor.style.left = e.clientX + 'px';
        cursor.style.top = e.clientY + 'px';
    });

    const links = document.querySelectorAll('a, button, .hover-card');
    links.forEach(link => {
        link.addEventListener('mouseenter', () => {
            cursor.style.transform = 'translate(-50%, -50%) scale(2.5)';
            cursor.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
            cursor.style.border = 'none';
        });
        link.addEventListener('mouseleave', () => {
            cursor.style.transform = 'translate(-50%, -50%) scale(1)';
            cursor.style.backgroundColor = 'transparent';
            cursor.style.border = '1px solid var(--doz-noir)';
        });
    });

    // 7. Page Transition
    const curtain = document.querySelector('.page-curtain');
    const linksToAnimate = document.querySelectorAll('a:not([target="_blank"]):not([href^="#"])');

    linksToAnimate.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const href = link.getAttribute('href');
            
            if(curtain) {
                curtain.classList.add('active');
                setTimeout(() => {
                    window.location.href = href;
                }, 600); // Wait for animation
            } else {
                window.location.href = href;
            }
        });
    });
    
    // Reveal curtain on load (optional, handled by default browser paint, but we can reverse it)
