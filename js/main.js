/* ============================================
   ligure.cc — Main JavaScript
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {

    // --- Theme Toggle ---
    const themeToggle = document.querySelector('.theme-toggle');
    const html = document.documentElement;

    // Check saved theme
    const saved = localStorage.getItem('theme');
    if (saved === 'dark') html.setAttribute('data-theme', 'dark');
    updateThemeIcon();

    themeToggle?.addEventListener('click', () => {
        const isDark = html.getAttribute('data-theme') === 'dark';
        html.setAttribute('data-theme', isDark ? 'light' : 'dark');
        localStorage.setItem('theme', isDark ? 'light' : 'dark');
        updateThemeIcon();
    });

    function updateThemeIcon() {
        if (!themeToggle) return;
        const isDark = html.getAttribute('data-theme') === 'dark';
        themeToggle.textContent = isDark ? '☀️' : '🌙';
    }

    // --- Mobile Menu ---
    const hamburger = document.querySelector('.nav-hamburger');
    const mobileMenu = document.querySelector('.mobile-menu');

    hamburger?.addEventListener('click', () => {
        hamburger.classList.toggle('open');
        mobileMenu?.classList.toggle('open');
    });

    // Close mobile menu on link click
    mobileMenu?.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            hamburger?.classList.remove('open');
            mobileMenu?.classList.remove('open');
        });
    });

    // --- Nav Active ---
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link[data-section]');

    function updateActiveNav() {
        let current = '';
        sections.forEach(section => {
            const top = section.offsetTop - 100;
            if (window.scrollY >= top) {
                current = section.getAttribute('id');
            }
        });
        navLinks.forEach(link => {
            link.classList.toggle('active', link.dataset.section === current);
        });
    }

    window.addEventListener('scroll', updateActiveNav, { passive: true });
    updateActiveNav();

    // --- Fade-in on scroll ---
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
});
