<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($PAGE_TITLE ?? 'Admin') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .admin-wrap  { display:flex; min-height:100vh; }
        .sidebar     { width:220px; background:#2d2d2d; flex-shrink:0; display:flex; flex-direction:column; position:sticky; top:0; height:100vh; overflow-y:auto; }
        .sidebar-logo{ padding:24px 20px 20px; border-bottom:1px solid rgba(201,168,76,.15); margin-bottom:8px; }
        .sidebar-logo div:first-child { font-family:'Cormorant Garamond',serif; font-size:16px; color:#fafafa; letter-spacing:.12em; }
        .sidebar-logo div:last-child  { font-size:7px; letter-spacing:.18em; text-transform:uppercase; color:#666; margin-top:2px; }
        .nav-item    { display:block; padding:10px 20px; font-size:9.5px; letter-spacing:.08em; text-transform:uppercase; color:rgba(255,255,255,.4); text-decoration:none; transition:all .2s; }
        .nav-item:hover, .nav-item.active { color:#c9a84c; background:rgba(201,168,76,.07); }
        .nav-item.logout { margin-top:auto; border-top:1px solid rgba(255,255,255,.06); }
        .main        { flex:1; background:#f5f5f5; padding:32px; }
        .stat-grid   { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:28px; }
        .stat-card   { background:#fff; padding:20px 22px; border-left:3px solid #c9a84c; }
        .stat-num    { font-family:'Cormorant Garamond',serif; font-size:36px; font-weight:300; color:#3a3a3a; line-height:1; }
        .stat-lbl    { font-size:8px; letter-spacing:.15em; text-transform:uppercase; color:#aaa; margin-top:5px; }
    </style>
</head>
<body>
<div class="admin-wrap">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div>ROYAL AUTOS</div>
            <div>Back-office</div>
        </div>
        <a class="nav-item active" href="/admin">Dashboard</a>
        <a class="nav-item" href="/admin/voitures">Voitures</a>
        <a class="nav-item" href="/admin/reservations">Réservations</a>
        <a class="nav-item" href="/" target="_blank">Voir le site</a>
        <a class="nav-item logout" href="/admin/logout">Déconnexion</a>
    </aside>

    <main class="main">
        <h1 style="font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:300;color:#3a3a3a;margin-bottom:22px">
            Bonjour, <?= htmlspecialchars($_SESSION['admin_nom'] ?? 'Admin') ?>
        </h1>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-num"><?= $nbVoitures ?? 0 ?></div>
                <div class="stat-lbl">Voitures disponibles</div>
            </div>
            <div class="stat-card">
                <div class="stat-num"><?= $nbReservations ?? 0 ?></div>
                <div class="stat-lbl">Réservations en attente</div>
            </div>
            <div class="stat-card">
                <div class="stat-num"><?= $nbMessages ?? 0 ?></div>
                <div class="stat-lbl">Messages non lus</div>
            </div>
        </div>

        <div style="background:#fff;padding:20px 24px">
            <div style="font-size:8px;letter-spacing:.18em;text-transform:uppercase;color:#c9a84c;margin-bottom:14px">Dernières réservations</div>
            <?php if (empty($reservations)): ?>
                <p style="font-size:11px;color:#bbb">Aucune réservation pour le moment.</p>
            <?php else: ?>
                <table style="width:100%;border-collapse:collapse;font-size:10px;color:#5a5a5a">
                    <thead><tr style="border-bottom:1px solid #eee">
                        <th style="text-align:left;padding:6px 10px;font-size:7.5px;letter-spacing:.1em;text-transform:uppercase;color:#bbb;font-weight:400">Réf</th>
                        <th style="text-align:left;padding:6px 10px;font-size:7.5px;letter-spacing:.1em;text-transform:uppercase;color:#bbb;font-weight:400">Client</th>
                        <th style="text-align:left;padding:6px 10px;font-size:7.5px;letter-spacing:.1em;text-transform:uppercase;color:#bbb;font-weight:400">Véhicule</th>
                        <th style="text-align:left;padding:6px 10px;font-size:7.5px;letter-spacing:.1em;text-transform:uppercase;color:#bbb;font-weight:400">Statut</th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($reservations as $r): ?>
                        <tr style="border-bottom:1px solid #f5f5f5">
                            <td style="padding:8px 10px"><?= htmlspecialchars($r['reference'] ?? '') ?></td>
                            <td style="padding:8px 10px"><?= htmlspecialchars(($r['nom'] ?? '') . ' ' . ($r['prenom'] ?? '')) ?></td>
                            <td style="padding:8px 10px"><?= htmlspecialchars(($r['marque'] ?? '') . ' ' . ($r['modele'] ?? '')) ?></td>
                            <td style="padding:8px 10px">
                                <?php $colors = ['en_attente'=>'#f39c12','payee'=>'#27ae60','annulee'=>'#e74c3c','confirmee'=>'#2980b9']; ?>
                                <span style="background:<?= $colors[$r['statut']] ?? '#aaa' ?>;color:#fff;padding:2px 8px;font-size:7.5px;letter-spacing:.08em;text-transform:uppercase">
                  <?= htmlspecialchars(str_replace('_', ' ', $r['statut'] ?? '')) ?>
                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>