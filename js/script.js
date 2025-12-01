// Script Doz - Coffee & Lagree

document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Initialisation des animations
    // On ajoute une classe au body pour signaler que le JS est prêt
    // Cela active le CSS qui cache les éléments .fade-in-up initialement
    document.body.classList.add('js-ready');

    const animatedElements = document.querySelectorAll('.fade-in-up');

    // 2. Navbar : Gestion du Menu Plein Écran Mobile
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    const navLinks = document.querySelectorAll('.nav-link');
    const body = document.body;

    // Toggle Menu
    navbarToggler.addEventListener('click', () => {
        // On toggle la classe show gérée par Bootstrap mais stylisée par nous
        // Bootstrap le fait auto, mais on peut ajouter une classe "menu-open" au body pour bloquer le scroll
        if (!navbarCollapse.classList.contains('show')) {
            body.style.overflow = 'hidden'; // Bloque le scroll
        } else {
            body.style.overflow = ''; // Débloque
        }
    });

    // Fermeture au clic sur un lien
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (navbarCollapse.classList.contains('show')) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                bsCollapse.hide();
                body.style.overflow = '';
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
        threshold: 0.15 // Déclenche un peu plus tard pour éviter l'effet "déjà là"
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Petit délai aléatoire si plusieurs éléments apparaissent en même temps
                // Mais on gère déjà ça avec CSS delay, donc on ajoute juste la classe
                entry.target.classList.add('visible');
                observer.unobserve(entry.target); 
            }
        });
    }, observerOptions);

    animatedElements.forEach(el => {
        observer.observe(el);
    });

});
