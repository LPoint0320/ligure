/* ligure.cc — Interactions & Theme */
(function() {
    const html = document.documentElement;
    const STORAGE_KEY = 'theme-mode';

    // ============ Theme (3-mode: auto/light/dark) ============
    function getTheme() { return localStorage.getItem(STORAGE_KEY) || 'auto'; }
    function applyTheme(mode) {
        if (mode === 'auto') {
            html.setAttribute('data-theme', matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light');
        } else {
            html.setAttribute('data-theme', mode);
        }
    }
    function cycleTheme() {
        const modes = ['auto', 'light', 'dark'];
        const cur = getTheme();
        const next = modes[(modes.indexOf(cur) + 1) % modes.length];
        localStorage.setItem(STORAGE_KEY, next);
        applyTheme(next);
        updateThemeIcon();
    }
    function updateThemeIcon() {
        const btn = document.querySelector('.theme-toggle');
        if (!btn) return;
        const icons = { light: '\u2600\uFE0F', dark: '\uD83C\uDF19', auto: '\u25D0' };
        btn.textContent = icons[getTheme()] || '\u25D0';
        btn.title = getTheme() === 'auto' ? 'Auto' : getTheme() === 'dark' ? 'Dark' : 'Light';
    }
    applyTheme(getTheme());
    updateThemeIcon();
    document.querySelector('.theme-toggle')?.addEventListener('click', cycleTheme);
    matchMedia('(prefers-color-scheme:dark)').addEventListener('change', function() {
        if (getTheme() === 'auto') applyTheme('auto');
    });

    // ============ Orbit Menu ============
    const orbitTrigger = document.getElementById('ot');
    const orbitMenu = document.getElementById('om');
    orbitTrigger?.addEventListener('click', function(e) {
        e.stopPropagation();
        orbitMenu?.classList.toggle('open');
    });
    document.addEventListener('click', function() {
        orbitMenu?.classList.remove('open');
    });

    // Orbit items & active tracking
    const orbitItems = document.querySelectorAll('.orbit-item');
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link[data-section]');

    orbitItems.forEach(function(item) {
        item.addEventListener('click', function() {
            var page = item.dataset.page;
            var el = document.getElementById(page);
            if (el) el.scrollIntoView({ behavior: 'smooth' });
            orbitMenu?.classList.remove('open');
        });
    });

    // ============ Mobile Menu ============
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    hamburger?.addEventListener('click', function() {
        hamburger.classList.toggle('open');
        mobileMenu?.classList.toggle('open');
    });
    mobileMenu?.querySelectorAll('a').forEach(function(a) {
        a.addEventListener('click', function() {
            hamburger?.classList.remove('open');
            mobileMenu?.classList.remove('open');
        });
    });

    // ============ Nav Scroll ============
    navLinks.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var el = document.getElementById(btn.dataset.section);
            if (el) el.scrollIntoView({ behavior: 'smooth' });
        });
    });

    function updateActiveNav() {
        var current = '';
        sections.forEach(function(s) {
            if (window.scrollY >= s.offsetTop - 150) current = s.id;
        });
        // Update nav links
        navLinks.forEach(function(l) {
            l.classList.toggle('active', l.dataset.section === current);
        });
        // Update orbit items
        orbitItems.forEach(function(item) {
            item.classList.toggle('active', item.dataset.page === current);
        });
    }
    window.addEventListener('scroll', updateActiveNav, { passive: true });
    updateActiveNav();

    // ============ Tab Filter ============
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            var filter = btn.dataset.filter;
            document.querySelectorAll('#pg .proj-card').forEach(function(card) {
                if (filter === 'all' || card.dataset.cat === filter) {
                    card.style.display = '';
                    card.style.animation = 'fadeCardIn .35s ease forwards';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // ============ Fade-in Observer ============
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });
    document.querySelectorAll('.fade-in').forEach(function(el) { observer.observe(el); });

    // ============ Nav shrink on scroll ============
    var lastScroll = 0;
    window.addEventListener('scroll', function() {
        var nav = document.getElementById('nav');
        if (!nav) return;
        var st = window.scrollY;
        if (st > 100 && st > lastScroll) {
            nav.style.opacity = '0.85';
        } else {
            nav.style.opacity = '1';
        }
        lastScroll = st;
    }, { passive: true });
})();
