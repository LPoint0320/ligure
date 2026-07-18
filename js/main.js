/* ligure.cc — Full interactions */
(function() {
    const h = document.documentElement;
    const SK = 'theme-mode';

    // --- Theme (3-mode) ---
    function get() { return localStorage.getItem(SK) || 'auto'; }
    function apply(m) {
        if (m === 'auto') h.setAttribute('data-theme', matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light');
        else h.setAttribute('data-theme', m);
    }
    function cycle() {
        const m = ['auto','light','dark']; const cur = get();
        const next = m[(m.indexOf(cur)+1)%m.length];
        localStorage.setItem(SK, next); apply(next); icon();
    }
    function icon() {
        const b = document.querySelector('.theme-toggle');
        if (!b) return;
        const i = { light: '\u2600\uFE0F', dark: '\uD83C\uDF19', auto: '\u25D0' };
        b.textContent = i[get()] || '\u25D0';
    }
    apply(get()); icon();
    document.querySelector('.theme-toggle')?.addEventListener('click', cycle);
    matchMedia('(prefers-color-scheme:dark)').addEventListener('change', () => { if (get()==='auto') apply('auto'); });

    // --- Orbit menu ---
    const ot = document.getElementById('ot');
    const om = document.getElementById('om');
    ot?.addEventListener('click', e => { e.stopPropagation(); om?.classList.toggle('open'); });
    document.addEventListener('click', () => om?.classList.remove('open'));
    document.querySelectorAll('.orbit-item').forEach(item => {
        item.addEventListener('click', () => {
            const page = item.dataset.page;
            const el = document.getElementById(page);
            if (el) el.scrollIntoView({ behavior: 'smooth' });
            om?.classList.remove('open');
        });
    });

    // --- Mobile ---
    const ham = document.querySelector('.nav-hamburger');
    const mm = document.querySelector('.mobile-menu');
    ham?.addEventListener('click', () => { ham.classList.toggle('open'); mm?.classList.toggle('open'); });
    mm?.querySelectorAll('a').forEach(a => a.addEventListener('click', () => { ham?.classList.remove('open'); mm?.classList.remove('open'); }));

    // --- Nav scroll ---
    document.querySelectorAll('.nav-link[data-section]').forEach(btn => {
        btn.addEventListener('click', () => {
            const el = document.getElementById(btn.dataset.section);
            if (el) el.scrollIntoView({ behavior:'smooth' });
        });
    });
    const secs = document.querySelectorAll('section[id]');
    const nls = document.querySelectorAll('.nav-link[data-section]');
    function upNav() {
        let cur = '';
        secs.forEach(s => { if (scrollY >= s.offsetTop - 150) cur = s.id; });
        nls.forEach(l => l.classList.toggle('active', l.dataset.section === cur));
    }
    addEventListener('scroll', upNav, { passive:true }); upNav();

    // --- Tab filter ---
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const f = btn.dataset.filter;
            document.querySelectorAll('#pg .proj-card').forEach(card => {
                card.style.display = (f==='all' || card.dataset.cat===f) ? '' : 'none';
            });
        });
    });

    // --- Fade-in ---
    const io = new IntersectionObserver((es) => { es.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); }); }, { threshold:.1 });
    document.querySelectorAll('.fade-in').forEach(el => io.observe(el));
})();
