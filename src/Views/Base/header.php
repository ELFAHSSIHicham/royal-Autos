<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Royal Autos — Véhicules d'occasion premium à Montauban. BMW, Mercedes, Audi, Porsche. Réservation en ligne sécurisée.">
    <title><?= htmlspecialchars($PAGE_TITLE ?? 'Royal Autos — Automobiles de prestige · Montauban') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        /* ── Dark Mode — redéfinition des variables CSS ─────────────── */
        body.dark {
            --g:     #2a2a2a;
            --g-d:   #333333;
            --g-dd:  #3d3d3d;
            --g-ddd: #111111;
            --g-l:   #2e2e2e;
            --g-ll:  #1e1e1e;
            --g-lll: #181818;
            --off:   #161616;
            --gold:  #c9a84c;
            --gold-l:#dfc078;

            background: #161616;
            color: #d0d0d0;
        }

        /* Textes sombres → clairs */
        body.dark .logo-main,
        body.dark .sec-h2,
        body.dark .h1,
        body.dark .eng-h2,
        body.dark .cta-h3,
        body.dark .card-nom,
        body.dark .card-price,
        body.dark .side-title,
        body.dark .ei-title,
        body.dark .ss-num { color: #e0e0e0; }

        body.dark .hero-desc,
        body.dark .eng-p,
        body.dark .ei-desc,
        body.dark .card-specs { color: #888; }

        body.dark .nav-a        { color: #777; }
        body.dark .nav-a:hover,
        body.dark .nav-a.active { color: #d0d0d0; }

        body.dark .logo-sub,
        body.dark .card-marque { color: var(--gold); opacity: .8; }

        /* Navbar & topbar */
        body.dark .navbar  { background: #1a1a1a; border-bottom-color: #2a2a2a; }
        body.dark .topbar  { background: #0f0f0f; }

        /* Cards */
        body.dark .card          { background: #1c1c1c; }
        body.dark .card-footer   { border-top-color: #2a2a2a; }
        body.dark .card-arrow    { background: #252525; border-color: #333; color: #666; }
        body.dark .card:hover .card-arrow { background: var(--gold); border-color: var(--gold); color: #fff; }
        body.dark .card-badge    { background: #252525; color: #888; border-color: #333; }

        /* Section / backgrounds */
        body.dark .section { background: #161616; }
        body.dark .eng     { background: #1a1a1a; }
        body.dark .eng-items { background: #222; }
        body.dark .ei      { background: #1a1a1a; }
        body.dark .cta     { background: #1a1a1a; border-color: #2a2a2a; }
        body.dark .strip   { background: #1c1c1c; border-color: #2a2a2a; }

        /* Hero sidebar */
        body.dark .hero-side { background: #1a1a1a; border-left-color: #2a2a2a; }
        body.dark .side-bottom,
        body.dark .ss-stat   { border-color: #2a2a2a; }

        /* Formulaires */
        body.dark .sf-input,
        body.dark .sf-select  { background: #1e1e1e; color: #ccc; border-bottom-color: #333; }
        body.dark .sf-lbl     { color: #555; }

        /* Boutons */
        body.dark .btn-grey       { background: #2a2a2a; color: #aaa; }
        body.dark .btn-grey:hover { background: #333; color: #ccc; }
        body.dark .search-btn     { background: #252525; color: #999; }
        body.dark .search-btn:hover { background: var(--gold); color: #fff; }

        /* Footer */
        body.dark .footer { background: #0f0f0f; }
        body.dark .footer-bottom { border-top-color: rgba(201,168,76,.1); }

        /* Fiche véhicule — panneaux inline */
        body.dark [style*="color:#3a3a3a"]  { color: #e0e0e0 !important; }
        body.dark [style*="color:#7a7a7a"]  { color: #999 !important; }
        body.dark [style*="color:#bbb"]     { color: #555 !important; }
        body.dark [style*="color:#aaa"]     { color: #555 !important; }
        body.dark [style*="color:#8a8a8a"]  { color: #666 !important; }

        /* Toggle bouton */
        .dark-toggle {
            background: none;
            border: 1px solid #ddd;
            cursor: pointer;
            padding: 7px 12px;
            display: flex;
            align-items: center;
            gap: 7px;
            font-family: inherit;
            font-size: 8px;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #999;
            transition: border-color .2s, color .2s, background .2s;
        }
        .dark-toggle:hover           { border-color: var(--gold); color: var(--gold); }
        body.dark .dark-toggle       { border-color: #333; color: #777; background: #1e1e1e; }
        body.dark .dark-toggle:hover { border-color: var(--gold); color: var(--gold); background: #252525; }

        @media print {
            .topbar, .navbar, .footer, .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-left">
        <div class="topbar-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="10" height="10">
                <circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>
            </svg>
            Lun – Sam &nbsp;·&nbsp; 9h–12h &nbsp;/&nbsp; 14h–19h
        </div>
        <div class="topbar-sep"></div>
        <div class="topbar-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="10" height="10">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.99 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.9 2.11h3a2 2 0 0 1 2 1.72c.128.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.572 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            <a href="tel:+33652015354" style="color:inherit;text-decoration:none">06 52 01 53 54</a>
        </div>
        <div class="topbar-sep"></div>
        <div class="topbar-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="10" height="10">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
            </svg>
            <a href="https://maps.google.com/?q=1279+Avenue+de+Toulouse,+82000+Montauban" target="_blank" rel="noopener" style="color:inherit;text-decoration:none">1279 Avenue de Toulouse, Montauban</a>
        </div>
    </div>
    <div class="topbar-right">
        <?php if (!empty($_SESSION['admin'])): ?>
            <a class="topbar-link" href="/admin">Administration</a>
        <?php endif; ?>
    </div>
</div>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
    <div class="logo-wrap">
        <a href="/" class="logo-link">
            <div class="logo-main">ROYAL AUTOS</div>
            <div class="logo-divider">
                <div class="logo-line"></div>
                <div class="logo-diamond"></div>
                <div class="logo-line"></div>
            </div>
            <div class="logo-sub">Automobiles de Prestige · Montauban</div>
        </a>
    </div>
    <div class="nav-center">
        <a class="nav-a <?= ($CURRENT_PATH ?? '') === '/' ? 'active' : '' ?>" href="/">Accueil</a>
        <a class="nav-a <?= str_starts_with($CURRENT_PATH ?? '', '/catalogue') ? 'active' : '' ?>" href="/catalogue">Occasions</a>
        <a class="nav-a <?= ($CURRENT_PATH ?? '') === '/contact' ? 'active' : '' ?>" href="/contact">Contact</a>
    </div>
    <div class="nav-right">
        <button class="dark-toggle no-print" id="darkToggle" onclick="toggleDark()" aria-label="Basculer le mode sombre">
            <svg id="icon-moon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
            <svg id="icon-sun" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                <circle cx="12" cy="12" r="5"/>
                <line x1="12" y1="1"  x2="12" y2="3"/>
                <line x1="12" y1="21" x2="12" y2="23"/>
                <line x1="4.22"  y1="4.22"  x2="5.64"  y2="5.64"/>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                <line x1="1"  y1="12" x2="3"  y2="12"/>
                <line x1="21" y1="12" x2="23" y2="12"/>
                <line x1="4.22"  y1="19.78" x2="5.64"  y2="18.36"/>
                <line x1="18.36" y1="5.64"  x2="19.78" y2="4.22"/>
            </svg>
            <span id="dark-label">Sombre</span>
        </button>
    </div>
    <div class="hamburger" id="hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
    </div>
</nav>

<script>
    function applyDark(on) {
        document.body.classList.toggle('dark', on);
        document.getElementById('icon-moon').style.display = on ? 'none' : '';
        document.getElementById('icon-sun').style.display  = on ? ''     : 'none';
        document.getElementById('dark-label').textContent  = on ? 'Clair' : 'Sombre';
    }
    function toggleDark() {
        const on = !document.body.classList.contains('dark');
        localStorage.setItem('royalautos_dark', on ? '1' : '0');
        applyDark(on);
    }
    /* Applique la préférence sauvegardée ou celle du système */
    (function () {
        const saved       = localStorage.getItem('royalautos_dark');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        applyDark(saved !== null ? saved === '1' : prefersDark);
    })();
</script>