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
        <a class="nav-btn" href="/catalogue">Prendre rendez-vous</a>
    </div>
    <div class="hamburger" id="hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
    </div>
</nav>