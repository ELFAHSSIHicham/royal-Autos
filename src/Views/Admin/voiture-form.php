<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $mode === 'edit' ? 'Modifier' : 'Ajouter' ?> une voiture — Admin Royal Autos</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .admin-wrap{display:flex;min-height:100vh}
        .sidebar{width:220px;background:#2d2d2d;flex-shrink:0;padding:24px 0}
        .sidebar-logo{padding:0 20px 20px;border-bottom:1px solid rgba(201,168,76,.15);margin-bottom:20px}
        .sidebar-logo div:first-child{font-family:'Cormorant Garamond',serif;font-size:16px;color:#fafafa;letter-spacing:.12em}
        .sidebar-logo div:last-child{font-size:7px;letter-spacing:.18em;text-transform:uppercase;color:#666;margin-top:2px}
        .nav-item{display:block;padding:10px 20px;font-size:9.5px;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.4);text-decoration:none}
        .nav-item:hover,.nav-item.active{color:#c9a84c}
        .main{flex:1;background:#f5f5f5;padding:32px}
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
        <a class="nav-item" href="/admin/logout">Déconnexion</a>
    </aside>

    <main class="main">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:300;color:#3a3a3a">
                <?= $mode === 'edit' ? 'Modifier le véhicule' : 'Ajouter un véhicule' ?>
            </h1>
            <a href="/admin/voitures" style="font-size:9px;color:#aaa;text-decoration:none">← Retour à la liste</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div style="background:#fff5f5;border-left:3px solid #e74c3c;padding:12px 16px;margin-bottom:20px;font-size:10px;color:#c0392b">
                <?php foreach ($errors as $e): ?><div>• <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php
        $action = $mode === 'edit'
                ? '/admin/voitures/modifier/' . (int)($voiture['id'] ?? 0)
                : '/admin/voitures/nouveau';
        $val = fn(string $k) => htmlspecialchars((string)($old[$k] ?? $voiture[$k] ?? ''));

        // Récupère toutes les marques pour le select
        $marques = \Models\Voiture\Voiture::getMarques();
        $currentMarqueId = (int)($old['marque_id'] ?? $voiture['marque_id'] ?? 0);
        $currentModeleId = (int)($old['modele_id'] ?? $voiture['modele_id'] ?? 0);
        ?>

        <form method="POST" action="<?= $action ?>" enctype="multipart/form-data">
            <?= \Shared\CsrfGuard::field() ?>
            <?php if ($mode === 'edit'): ?>
                <input type="hidden" name="image_actuelle" value="<?= htmlspecialchars($voiture['image_principale'] ?? '') ?>">
            <?php endif; ?>

            <!-- ── Lookup immatriculation ──────────────────────────────────── -->
            <div style="background:#f9f6ee;border:1px solid #e8d98a;border-radius:8px;padding:16px;margin-bottom:24px">
                <label style="font-weight:600;display:block;margin-bottom:8px">🔍 Pré-remplir via immatriculation</label>
                <div style="display:flex;gap:8px">
                    <input type="text" id="immat-lookup" placeholder="AB-123-CD"
                           maxlength="9" style="flex:1;text-transform:uppercase" class="sf-input">
                    <button type="button" id="btn-immat-lookup" class="btn-gold">Rechercher</button>
                </div>
                <div id="immat-status" style="display:none;margin-top:8px;font-size:.85rem"></div>
                <small style="color:#888;margin-top:4px;display:block">⚠️ Le kilométrage n'est pas récupérable — à saisir manuellement</small>
            </div>

            <!-- ── Informations principales ───────────────────────────────── -->
            <div style="background:#fff;padding:24px;margin-bottom:16px">
                <div style="font-size:8px;letter-spacing:.18em;text-transform:uppercase;color:#c9a84c;margin-bottom:18px">Informations principales</div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">

                    <!-- Marque (select) -->
                    <div class="sf">
                        <div class="sf-lbl">Marque *</div>
                        <select name="marque_id" id="select-marque" class="sf-select" required>
                            <option value="">— Choisir —</option>
                            <?php foreach ($marques as $m): ?>
                                <option value="<?= $m['id'] ?>" <?= $currentMarqueId === (int)$m['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Modèle (select dynamique) -->
                    <div class="sf" id="sf-modele">
                        <div class="sf-lbl">Modèle *</div>
                        <select name="modele_id" id="select-modele" class="sf-select">
                            <option value="">— Choisir la marque d'abord —</option>
                        </select>
                    </div>

                    <!-- Modèle texte (fallback / affiché en base) -->
                    <div class="sf">
                        <div class="sf-lbl">Modèle (texte libre)</div>
                        <input type="text" name="modele" id="input-modele" class="sf-input"
                               value="<?= $val('modele') ?>"
                               placeholder="Rempli automatiquement ou saisir manuellement">
                    </div>

                    <div class="sf"><div class="sf-lbl">Année *</div>
                        <input type="number" name="annee" class="sf-input" value="<?= $val('annee') ?>" min="1990" max="<?= date('Y')+1 ?>" required>
                    </div>
                    <div class="sf"><div class="sf-lbl">Prix (€) *</div>
                        <input type="number" name="prix" class="sf-input" value="<?= $val('prix') ?>" step="100" required>
                    </div>
                    <div class="sf"><div class="sf-lbl">Kilométrage</div>
                        <input type="number" name="kilometrage" class="sf-input" value="<?= $val('kilometrage') ?>">
                    </div>
                    <div class="sf"><div class="sf-lbl">Puissance (ch)</div>
                        <input type="number" name="puissance" class="sf-input" value="<?= $val('puissance') ?>">
                    </div>
                </div>
            </div>

            <!-- ── Caractéristiques ───────────────────────────────────────── -->
            <div style="background:#fff;padding:24px;margin-bottom:16px">
                <div style="font-size:8px;letter-spacing:.18em;text-transform:uppercase;color:#c9a84c;margin-bottom:18px">Caractéristiques</div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
                    <div class="sf"><div class="sf-lbl">Carburant</div>
                        <select name="carburant" id="input-carburant" class="sf-select">
                            <?php foreach (['Essence','Diesel','Hybride','Électrique','GPL'] as $c): ?>
                                <option <?= $val('carburant') === $c ? 'selected' : '' ?>><?= $c ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="sf"><div class="sf-lbl">Transmission</div>
                        <select name="transmission" id="input-transmission" class="sf-select">
                            <?php foreach (['Manuelle','Automatique'] as $t): ?>
                                <option <?= $val('transmission') === $t ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="sf"><div class="sf-lbl">Couleur</div>
                        <input type="text" name="couleur" id="input-couleur" class="sf-input" value="<?= $val('couleur') ?>">
                    </div>
                    <div class="sf"><div class="sf-lbl">Statut</div>
                        <select name="statut" class="sf-select">
                            <?php foreach (['disponible','reserve','vendu','maintenance'] as $s): ?>
                                <option value="<?= $s ?>" <?= $val('statut') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div style="margin-top:16px;display:flex;align-items:center;gap:8px">
                    <input type="checkbox" name="est_vedette" id="vedette" value="1"
                            <?= (($old['est_vedette'] ?? $voiture['est_vedette'] ?? 0) == 1) ? 'checked' : '' ?>>
                    <label for="vedette" style="font-size:10px;color:#5a5a5a">Mettre en vedette (affiché sur la page d'accueil)</label>
                </div>
            </div>

            <!-- ── Description & Photo ────────────────────────────────────���─ -->
            <div style="background:#fff;padding:24px;margin-bottom:16px">
                <div style="font-size:8px;letter-spacing:.18em;text-transform:uppercase;color:#c9a84c;margin-bottom:18px">Description & Photo</div>
                <div class="sf" style="margin-bottom:16px">
                    <div class="sf-lbl">Description</div>
                    <textarea name="description" class="sf-input" rows="5" style="resize:vertical"><?= $val('description') ?></textarea>
                </div>
                <div class="sf">
                    <div class="sf-lbl">Photo principale</div>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="sf-input" style="padding:6px">
                    <?php if (!empty($voiture['image_principale'])): ?>
                        <div style="margin-top:8px">
                            <img src="<?= htmlspecialchars($voiture['image_principale']) ?>" style="height:80px;object-fit:cover">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn-gold" style="padding:13px 30px">
                <?= $mode === 'edit' ? 'Enregistrer les modifications' : 'Ajouter le véhicule' ?>
            </button>
        </form>
    </main>
</div>

<script>
    /* ── Marque → Modèles dynamiques (admin form) ───────────────────────────── */
    document.addEventListener('DOMContentLoaded', () => {
        const selMarque  = document.getElementById('select-marque');
        const selModele  = document.getElementById('select-modele');
        const inputModele = document.getElementById('input-modele');

        if (!selMarque || !selModele) return;

        // Au chargement en mode edit : recharge les modèles si une marque est déjà sélectionnée
        if (selMarque.value) chargeModeles(selMarque.value, <?= $currentModeleId ?>);

        selMarque.addEventListener('change', () => {
            chargeModeles(selMarque.value, 0);
        });

        selModele.addEventListener('change', () => {
            // Remplit automatiquement le champ texte modele
            const opt = selModele.options[selModele.selectedIndex];
            if (opt && opt.value) inputModele.value = opt.text;
        });

        async function chargeModeles(marqueId, selectedId) {
            if (!marqueId) {
                selModele.innerHTML = '<option value="">— Choisir la marque d\'abord —</option>';
                return;
            }
            const res     = await fetch(`/api/modeles?marque_id=${marqueId}`);
            const modeles = await res.json();

            selModele.innerHTML = '<option value="">— Choisir un modèle —</option>';
            modeles.forEach(m => {
                const opt = document.createElement('option');
                opt.value       = m.id;
                opt.textContent = m.nom;
                if ((int = parseInt(m.id)) === selectedId) opt.selected = true;
                selModele.appendChild(opt);
            });
        }
    });
</script>
<script src="/assets/js/immat.js"></script>
</body>
</html>