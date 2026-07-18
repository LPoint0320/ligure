/* ligure.cc — Theme & Navigation */

(function() {
    const html = document.documentElement;
    const STORAGE_KEY = 'theme-mode'; // 'light' | 'dark' | 'auto'

    // --- 3-mode theme ---
    function getTheme() {
        return localStorage.getItem(STORAGE_KEY) || 'auto';
    }
    function saveTheme(mode) { localStorage.setItem(STORAGE_KEY, mode); }

    function applyTheme(mode) {
        if (mode === 'auto') {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            html.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
        } else {
            html.setAttribute('data-theme', mode);
        }
    }

    function getEffectiveTheme() {
        return html.getAttribute('data-theme') || 'light';
    }

    function cycleTheme() {
        const modes = ['auto', 'light', 'dark'];
        const current = getTheme();
        const idx = modes.indexOf(current);
        const next = modes[(idx + 1) % modes.length];
        saveTheme(next);
        applyTheme(next);
        updateToggleIcon();
    }

    function updateToggleIcon() {
        const btn = document.querySelector('.theme-toggle');
        if (!btn) return;
        const mode = getTheme();
        const icons = { light: '☀️', dark: '🌙', auto: '◐' };
        btn.textContent = icons[mode] || '◐';
        btn.title = mode === 'auto' ? 'Auto · 跟随系统' : mode === 'dark' ? 'Dark · 夜间模式' : 'Light · 日间模式';
    }

    // Init
    applyTheme(getTheme());
    updateToggleIcon();

    // Toggle button
    document.querySelector('.theme-toggle')?.addEventListener('click', cycleTheme);

    // Listen for system changes when in auto mode
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (getTheme() === 'auto') applyTheme('auto');
    });

    // --- Mobile menu ---
    const hamburger = document.querySelector('.nav-hamburger');
    const mobileMenu = document.querySelector('.mobile-menu');
    hamburger?.addEventListener('click', () => {
        hamburger.classList.toggle('open');
        mobileMenu?.classList.toggle('open');
    });
    mobileMenu?.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            hamburger?.classList.remove('open');
            mobileMenu?.classList.remove('open');
        });
    });

    // --- Nav active + smooth scroll ---
    document.querySelectorAll('.nav-link[data-section]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.section;
            const el = document.getElementById(id);
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link[data-section]');
    function updateNav() {
        let current = '';
        sections.forEach(s => {
            if (window.scrollY >= s.offsetTop - 120) current = s.id;
        });
        navLinks.forEach(link => {
            link.classList.toggle('active', link.dataset.section === current);
        });
    }
    window.addEventListener('scroll', updateNav, { passive: true });
    updateNav();

    // --- Fade-in ---
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-in').forEach(el => obs.observe(el));
})();
