<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title>Voitures — Admin Royal Autos</title>
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
        <a class="nav-item active" href="/admin/voitures">Voitures</a>
        <a class="nav-item" href="/admin/reservations">Réservations</a>
        <a class="nav-item" href="/" target="_blank">Voir le site</a>
        <a class="nav-item logout" href="/admin/logout">Déconnexion</a>
    </aside>
    <main class="main">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:300;color:#3a3a3a">Gestion des voitures</h1>
            <a href="/admin/voitures/nouveau" class="btn-gold" style="text-decoration:none;display:inline-flex;gap:6px;padding:10px 20px">+ Ajouter</a>
        </div>

        <?php if (!empty($success)): ?>
            <div style="background:#f0faf4;border-left:3px solid #27ae60;padding:10px 16px;margin-bottom:18px;font-size:10px;color:#1e8449"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div style="background:#fff;overflow:hidden">
            <table style="width:100%;border-collapse:collapse;font-size:10px;color:#5a5a5a">
                <thead><tr style="border-bottom:2px solid #e8e8e8;background:#fafafa">
                    <?php foreach (['Photo','Marque / Modèle','Année','Prix','Km','Statut','Actions'] as $h): ?>
                        <th style="text-align:left;padding:10px 14px;font-size:7.5px;letter-spacing:.1em;text-transform:uppercase;color:#bbb;font-weight:400"><?= $h ?></th>
                    <?php endforeach; ?>
                </tr></thead>
                <tbody>
                <?php foreach ($voitures ?? [] as $v): ?>
                    <tr style="border-bottom:1px solid #f5f5f5">
                        <td style="padding:8px 14px">
                            <?php if ($v['image_principale']): ?>
                                <img src="<?= htmlspecialchars($v['image_principale']) ?>" style="width:60px;height:40px;object-fit:cover">
                            <?php else: ?>
                                <div style="width:60px;height:40px;background:#e8e8e8"></div>
                            <?php endif; ?>
                        </td>
                        <td style="padding:8px 14px">
                            <div style="font-weight:500;color:#3a3a3a"><?= htmlspecialchars($v['marque']) ?></div>
                            <div style="color:#aaa"><?= htmlspecialchars($v['modele']) ?></div>
                        </td>
                        <td style="padding:8px 14px"><?= (int)$v['annee'] ?></td>
                        <td style="padding:8px 14px"><?= number_format((float)$v['prix'],0,',',' ') ?> €</td>
                        <td style="padding:8px 14px"><?= number_format((int)$v['kilometrage'],0,',',' ') ?></td>
                        <td style="padding:8px 14px">
                            <?php
                            $sc = [
                                    'disponible'     => ['bg'=>'#27ae60','label'=>'Disponible'],
                                    'reserve'        => ['bg'=>'#f39c12','label'=>'Réservé'],
                                    'vendu'          => ['bg'=>'#95a5a6','label'=>'Vendu'],
                                    'en_preparation' => ['bg'=>'#2980b9','label'=>'En préparation'],
                            ];
                            $s = $sc[$v['statut']] ?? ['bg'=>'#aaa','label'=>ucfirst($v['statut'])];
                            ?>
                            <span style="background:<?= $s['bg'] ?>;color:#fff;padding:2px 8px;font-size:7.5px;letter-spacing:.06em;text-transform:uppercase"><?= $s['label'] ?></span>
                        </td>
                        <td style="padding:8px 14px">
                            <a href="/admin/voitures/modifier/<?= (int)$v['id'] ?>" style="color:#c9a84c;font-size:9px;text-decoration:none;margin-right:10px">Modifier</a>
                            <form method="POST" action="/admin/voitures/supprimer/<?= (int)$v['id'] ?>" style="display:inline" onsubmit="return confirm('Supprimer ce véhicule ?')">
                                <?= \Shared\CsrfGuard::field() ?>
                                <button type="submit" style="background:none;border:none;color:#e74c3c;font-size:9px;cursor:pointer;font-family:'Jost',sans-serif">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>