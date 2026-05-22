<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title>Réservations — Admin Royal Autos</title>
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
    </style>
</head>
<body>
<div class="admin-wrap">
    <aside class="sidebar">
        <div class="sidebar-logo"><div>ROYAL AUTOS</div><div>Back-office</div></div>
        <a class="nav-item" href="/admin">Dashboard</a>
        <a class="nav-item" href="/admin/voitures">Voitures</a>
        <a class="nav-item active" href="/admin/reservations">Réservations</a>
        <a class="nav-item" href="/" target="_blank">Voir le site</a>
        <a class="nav-item logout" href="/admin/logout">Déconnexion</a>
    </aside>
    <main class="main">
        <h1 style="font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:300;color:#3a3a3a;margin-bottom:24px">Réservations</h1>
        <div style="background:#fff;overflow:auto">
            <table style="width:100%;border-collapse:collapse;font-size:10px;color:#5a5a5a;min-width:700px">
                <thead><tr style="border-bottom:2px solid #e8e8e8;background:#fafafa">
                    <?php foreach (['Réf','Date','Client','Email','Téléphone','Véhicule','Montant','Acompte','Statut'] as $h): ?>
                        <th style="text-align:left;padding:10px 12px;font-size:7.5px;letter-spacing:.1em;text-transform:uppercase;color:#bbb;font-weight:400"><?= $h ?></th>
                    <?php endforeach; ?>
                </tr></thead>
                <tbody>
                <?php foreach ($reservations ?? [] as $r): ?>
                    <tr style="border-bottom:1px solid #f5f5f5">
                        <td style="padding:9px 12px;font-weight:500;color:#3a3a3a"><?= htmlspecialchars($r['reference'] ?? '') ?></td>
                        <td style="padding:9px 12px;color:#aaa"><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
                        <td style="padding:9px 12px"><?= htmlspecialchars(($r['nom'] ?? '') . ' ' . ($r['prenom'] ?? '')) ?></td>
                        <td style="padding:9px 12px"><?= htmlspecialchars($r['email'] ?? '') ?></td>
                        <td style="padding:9px 12px"><?= htmlspecialchars($r['telephone'] ?? '') ?></td>
                        <td style="padding:9px 12px"><?= htmlspecialchars(($r['marque'] ?? '') . ' ' . ($r['modele'] ?? '')) ?></td>
                        <td style="padding:9px 12px"><?= number_format((float)($r['montant'] ?? 0),0,',',' ') ?> €</td>
                        <td style="padding:9px 12px"><?= number_format((float)($r['acompte'] ?? 0),0,',',' ') ?> €</td>
                        <td style="padding:9px 12px">
                            <?php $sc = ['en_attente'=>'#f39c12','payee'=>'#27ae60','annulee'=>'#e74c3c','confirmee'=>'#2980b9','terminee'=>'#95a5a6']; ?>
                            <span style="background:<?= $sc[$r['statut']] ?? '#aaa' ?>;color:#fff;padding:2px 8px;font-size:7.5px;letter-spacing:.06em;text-transform:uppercase">
                <?= htmlspecialchars(str_replace('_', ' ', $r['statut'] ?? '')) ?>
              </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($reservations)): ?>
                    <tr><td colspan="9" style="padding:30px;text-align:center;color:#bbb">Aucune réservation.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>