<?php
$mode    = $mode    ?? 'create';
$voiture = $voiture ?? [];
$old     = $old     ?? [];
$errors  = $errors  ?? [];
$images  = $images  ?? [];
?>
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

        .sf-row{display:flex;gap:8px;align-items:flex-end}
        .sf-row .sf{flex:1}
        .btn-plus-wrap{display:flex;flex-direction:column}
        .btn-plus-wrap .sf-lbl-ghost{visibility:hidden;font-size:9px;margin-bottom:4px}
        .btn-plus{background:#c9a84c;color:#fff;border:none;width:32px;height:32px;font-size:20px;line-height:1;cursor:pointer;flex-shrink:0;display:flex;align-items:center;justify-content:center}
        .btn-plus:hover{background:#b8973b}

        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center}
        .modal-overlay.open{display:flex}
        .modal{background:#fff;padding:28px;width:340px;max-width:90vw}
        .modal h3{font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:300;margin-bottom:16px;color:#3a3a3a}
        .modal-btns{display:flex;gap:8px;margin-top:16px;justify-content:flex-end}
        .btn-cancel{background:#e8e8e8;color:#5a5a5a;border:none;padding:8px 16px;font-size:9px;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;font-family:'Jost',sans-serif}

        /* Galerie upload */
        .gallery-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:10px;margin-top:12px}
        .gallery-item{position:relative;aspect-ratio:4/3;background:#f0f0f0;overflow:hidden}
        .gallery-item img{width:100%;height:100%;object-fit:cover}
        .gallery-item .del-btn{position:absolute;top:4px;right:4px;background:rgba(231,76,60,.85);color:#fff;border:none;border-radius:50%;width:22px;height:22px;font-size:13px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center}
        .gallery-item .principal-badge{position:absolute;bottom:4px;left:4px;background:#c9a84c;color:#fff;font-size:7px;letter-spacing:.08em;text-transform:uppercase;padding:2px 6px}
        .gallery-item .set-principal{position:absolute;bottom:4px;left:4px;background:rgba(0,0,0,.5);color:#fff;font-size:7px;letter-spacing:.08em;text-transform:uppercase;padding:2px 6px;cursor:pointer;border:none;font-family:'Jost',sans-serif}
        .upload-zone{border:2px dashed #d0d0d0;padding:24px;text-align:center;cursor:pointer;margin-top:12px;transition:border-color .2s}
        .upload-zone:hover{border-color:#c9a84c}
        .upload-zone input{display:none}
        .upload-zone p{font-size:10px;color:#aaa;margin:0}
        .upload-zone span{font-size:9px;color:#c9a84c}
        #preview-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:10px;margin-top:12px}
        .preview-item{position:relative;aspect-ratio:4/3;background:#f0f0f0;overflow:hidden}
        .preview-item img{width:100%;height:100%;object-fit:cover}
        .preview-item .del-preview{position:absolute;top:4px;right:4px;background:rgba(231,76,60,.85);color:#fff;border:none;border-radius:50%;width:22px;height:22px;font-size:13px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center}
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

        $v = function(string $champ) use ($old, $voiture): string {
            return htmlspecialchars((string)($old[$champ] ?? $voiture[$champ] ?? ''));
        };

        $marques         = \Models\Voiture\Voiture::getMarques();
        $currentMarqueId = (int)($old['marque_id'] ?? $voiture['marque_id'] ?? 0);
        $currentModeleId = (int)($old['modele_id'] ?? $voiture['modele_id'] ?? 0);

        $dateMC  = $old['date_mise_circulation'] ?? $voiture['date_mise_circulation'] ?? '';
        $dateIM  = $old['date_immatriculation']  ?? $voiture['date_immatriculation']  ?? '';
        $dateMCf = ($dateMC && strtotime($dateMC)) ? date('d/m/Y', strtotime($dateMC)) : '';
        $dateIMf = ($dateIM && strtotime($dateIM)) ? date('d/m/Y', strtotime($dateIM)) : '';

        $imagePrincipale = $voiture['image_principale'] ?? '';
        $nbImagesExist   = count($images);
        $maxPhotos       = 40;
        ?>

        <form method="POST" action="<?= $action ?>" enctype="multipart/form-data" id="voiture-form">
            <?= \Shared\CsrfGuard::field() ?>
            <input type="hidden" name="image_principale_url" id="image_principale_url" value="<?= htmlspecialchars($imagePrincipale) ?>">

            <!-- ── Lookup immatriculation ──────────────────────────────────── -->
            <div style="background:#f9f6ee;border:1px solid #e8d98a;border-radius:8px;padding:16px;margin-bottom:24px">
                <label style="font-weight:600;display:block;margin-bottom:8px">🔍 Pré-remplir via immatriculation</label>
                <div style="display:flex;gap:8px">
                    <input type="text" id="immat-lookup" placeholder="AB-123-CD" maxlength="9"
                           style="flex:1;text-transform:uppercase" class="sf-input">
                    <button type="button" id="btn-immat-lookup" class="btn-gold">Rechercher</button>
                </div>
                <div id="immat-status" style="display:none;margin-top:8px;font-size:.85rem"></div>
                <small style="color:#888;margin-top:4px;display:block">⚠️ Le kilométrage n'est pas récupérable — à saisir manuellement</small>
            </div>

            <!-- ── Informations principales ───────────────────────────────── -->
            <div style="background:#fff;padding:24px;margin-bottom:16px">
                <div style="font-size:8px;letter-spacing:.18em;text-transform:uppercase;color:#c9a84c;margin-bottom:18px">Informations principales</div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">

                    <div>
                        <div class="sf-row">
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
                            <div class="btn-plus-wrap">
                                <span class="sf-lbl-ghost">.</span>
                                <button type="button" class="btn-plus" title="Ajouter une marque" onclick="openModal('marque')">+</button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="sf-row">
                            <div class="sf">
                                <div class="sf-lbl">Modèle *</div>
                                <select name="modele_id" id="select-modele" class="sf-select">
                                    <option value="">— Choisir la marque d'abord —</option>
                                </select>
                            </div>
                            <div class="btn-plus-wrap">
                                <span class="sf-lbl-ghost">.</span>
                                <button type="button" class="btn-plus" title="Ajouter un modèle" onclick="openModal('modele')">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="sf">
                        <div class="sf-lbl">Modèle (texte libre)</div>
                        <input type="text" name="modele" id="input-modele" class="sf-input"
                               value="<?= $v('modele') ?>" placeholder="Rempli automatiquement ou saisir manuellement">
                    </div>

                    <div class="sf"><div class="sf-lbl">Année *</div>
                        <input type="number" name="annee" class="sf-input" value="<?= $v('annee') ?>" min="1900" max="<?= date('Y') ?>" required>
                    </div>
                    <div class="sf"><div class="sf-lbl">Prix (€) *</div>
                        <input type="number" name="prix" class="sf-input" value="<?= $v('prix') ?>" step="100" required>
                    </div>
                    <div class="sf"><div class="sf-lbl">Kilométrage</div>
                        <input type="number" name="kilometrage" class="sf-input" value="<?= $v('kilometrage') ?>">
                    </div>
                    <div class="sf"><div class="sf-lbl">Puissance (ch)</div>
                        <input type="number" name="puissance" class="sf-input" value="<?= $v('puissance') ?>">
                    </div>
                    <div class="sf"><div class="sf-lbl">Première mise en circulation (jj/mm/aaaa)</div>
                        <input type="text" name="date_mise_circulation" class="sf-input sf-date"
                               placeholder="jj/mm/aaaa" maxlength="10" value="<?= htmlspecialchars($dateMCf) ?>">
                    </div>
                    <div class="sf"><div class="sf-lbl">Date 1ère immatriculation (jj/mm/aaaa)</div>
                        <input type="text" name="date_immatriculation" class="sf-input sf-date"
                               placeholder="jj/mm/aaaa" maxlength="10" value="<?= htmlspecialchars($dateIMf) ?>">
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
                                <option <?= $v('carburant') === $c ? 'selected' : '' ?>><?= $c ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="sf"><div class="sf-lbl">Transmission</div>
                        <select name="transmission" id="input-transmission" class="sf-select">
                            <?php foreach (['Manuelle','Automatique'] as $t): ?>
                                <option <?= $v('transmission') === $t ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="sf"><div class="sf-lbl">Motorisation</div>
                        <input type="text" name="motorisation" class="sf-input" value="<?= $v('motorisation') ?>" placeholder="Ex : 2.0 TDI 150ch">
                    </div>
                    <div class="sf"><div class="sf-lbl">Finition</div>
                        <input type="text" name="finition" class="sf-input" value="<?= $v('finition') ?>" placeholder="Ex : Sport Line, AMG, S-Line…">
                    </div>
                    <div class="sf"><div class="sf-lbl">Nombre de portes</div>
                        <select name="portes" class="sf-select">
                            <?php foreach ([2,3,4,5] as $p): ?>
                                <option value="<?= $p ?>" <?= (int)($old['portes'] ?? $voiture['portes'] ?? 5) === $p ? 'selected' : '' ?>><?= $p ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="sf"><div class="sf-lbl">Nombre de places</div>
                        <select name="places" class="sf-select">
                            <?php foreach ([2,4,5,6,7,8,9] as $p): ?>
                                <option value="<?= $p ?>" <?= (int)($old['places'] ?? $voiture['places'] ?? 5) === $p ? 'selected' : '' ?>><?= $p ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="sf"><div class="sf-lbl">Couleur</div>
                        <input type="text" name="couleur" id="input-couleur" class="sf-input" value="<?= $v('couleur') ?>">
                    </div>
                    <div class="sf"><div class="sf-lbl">Statut</div>
                        <select name="statut" class="sf-select">
                            <?php foreach ([
                                                   'disponible'     => 'Disponible',
                                                   'reserve'        => 'Réservé',
                                                   'vendu'          => 'Vendu',
                                                   'en_preparation' => 'En préparation',
                                           ] as $sVal => $sLabel): ?>
                                <option value="<?= $sVal ?>" <?= $v('statut') === $sVal ? 'selected' : '' ?>><?= $sLabel ?></option>
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

            <!-- ── Description ────────────────────────────────────────────── -->
            <div style="background:#fff;padding:24px;margin-bottom:16px">
                <div style="font-size:8px;letter-spacing:.18em;text-transform:uppercase;color:#c9a84c;margin-bottom:18px">Description</div>
                <div class="sf">
                    <textarea name="description" class="sf-input" rows="5" style="resize:vertical"><?= $v('description') ?></textarea>
                </div>
            </div>

            <!-- ── Photos ───────────────────────────���─────────────────────── -->
            <div style="background:#fff;padding:24px;margin-bottom:16px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                    <div style="font-size:8px;letter-spacing:.18em;text-transform:uppercase;color:#c9a84c">Photos
                        <span id="photo-count" style="color:#aaa;font-size:9px;letter-spacing:0;text-transform:none;margin-left:6px">(<?= $nbImagesExist ?>/<?= $maxPhotos ?>)</span>
                    </div>
                    <div style="font-size:9px;color:#aaa">Cliquez sur une photo pour la définir comme principale</div>
                </div>

                <!-- Photos existantes (mode edit) -->
                <?php if (!empty($images)): ?>
                    <div class="gallery-grid" id="existing-gallery">
                        <?php foreach ($images as $img): ?>
                            <div class="gallery-item" data-id="<?= (int)$img['id'] ?>">
                                <img src="<?= htmlspecialchars($img['url']) ?>" alt="">
                                <?php if ($img['url'] === $imagePrincipale): ?>
                                    <span class="principal-badge">Principale</span>
                                <?php else: ?>
                                    <button type="button" class="set-principal" onclick="setPrincipal('<?= htmlspecialchars($img['url']) ?>', this)">Principale</button>
                                <?php endif; ?>
                                <button type="button" class="del-btn" onclick="deleteImage(<?= (int)$img['id'] ?>, this)" title="Supprimer">×</button>
                                <input type="hidden" name="images_existantes[]" value="<?= (int)$img['id'] ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Zone d'ajout de nouvelles photos -->
                <div class="upload-zone" id="upload-zone" onclick="document.getElementById('input-photos').click()">
                    <p>Glisser-déposer ou <span>cliquer pour ajouter des photos</span></p>
                    <p style="margin-top:4px">JPG, PNG, WEBP — max 5 Mo par photo — max <?= $maxPhotos ?> photos au total</p>
                    <input type="file" id="input-photos" name="nouvelles_photos[]" multiple
                           accept="image/jpeg,image/png,image/webp">
                </div>

                <!-- Prévisualisation des nouvelles photos -->
                <div id="preview-grid"></div>
            </div>

            <button type="submit" class="btn-gold" style="padding:13px 30px">
                <?= $mode === 'edit' ? 'Enregistrer les modifications' : 'Ajouter le véhicule' ?>
            </button>
        </form>
    </main>
</div>

<!-- ── Modal ajout marque ─────────────────────────────────────────────────── -->
<div class="modal-overlay" id="modal-marque">
    <div class="modal">
        <h3>Ajouter une marque</h3>
        <div class="sf">
            <div class="sf-lbl">Nom de la marque *</div>
            <input type="text" id="input-new-marque" class="sf-input" placeholder="Ex : Porsche">
        </div>
        <div id="modal-marque-error" style="color:#e74c3c;font-size:10px;margin-top:6px;display:none"></div>
        <div class="modal-btns">
            <button type="button" class="btn-cancel" onclick="closeModal('marque')">Annuler</button>
            <button type="button" class="btn-gold" style="padding:8px 16px" onclick="submitMarque()">Ajouter</button>
        </div>
    </div>
</div>

<!-- ── Modal ajout modèle ─────────────────────────────────────────────────── -->
<div class="modal-overlay" id="modal-modele">
    <div class="modal">
        <h3>Ajouter un modèle</h3>
        <div class="sf" style="margin-bottom:12px">
            <div class="sf-lbl">Marque *</div>
            <select id="modal-modele-marque" class="sf-select">
                <option value="">— Choisir —</option>
                <?php foreach ($marques as $m): ?>
                    <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="sf">
            <div class="sf-lbl">Nom du modèle *</div>
            <input type="text" id="input-new-modele" class="sf-input" placeholder="Ex : Classe C">
        </div>
        <div id="modal-modele-error" style="color:#e74c3c;font-size:10px;margin-top:6px;display:none"></div>
        <div class="modal-btns">
            <button type="button" class="btn-cancel" onclick="closeModal('modele')">Annuler</button>
            <button type="button" class="btn-gold" style="padding:8px 16px" onclick="submitModele()">Ajouter</button>
        </div>
    </div>
</div>

<script>
    const MAX_PHOTOS  = <?= $maxPhotos ?>;
    let   nbExistant  = <?= $nbImagesExist ?>;
    let   filesValides = [];

    /* ── Marque → Modèles dynamiques ─────────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', () => {
        const selMarque   = document.getElementById('select-marque');
        const selModele   = document.getElementById('select-modele');
        const inputModele = document.getElementById('input-modele');

        if (selMarque.value) chargeModeles(selMarque.value, <?= $currentModeleId ?>);
        selMarque.addEventListener('change', () => chargeModeles(selMarque.value, 0));
        selModele.addEventListener('change', () => {
            const opt = selModele.options[selModele.selectedIndex];
            if (opt && opt.value) inputModele.value = opt.text;
        });

        async function chargeModeles(marqueId, selectedId) {
            if (!marqueId) { selModele.innerHTML = '<option value="">— Choisir la marque d\'abord —</option>'; return; }
            const res     = await fetch(`/api/modeles?marque_id=${marqueId}`);
            const modeles = await res.json();
            selModele.innerHTML = '<option value="">— Choisir un modèle —</option>';
            modeles.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id; opt.textContent = m.nom;
                if (parseInt(m.id) === selectedId) opt.selected = true;
                selModele.appendChild(opt);
            });
        }
        window._chargeModeles = chargeModeles;
    });

    /* ── Upload photos ───────────────────────────────────────────────────────── */
    const inputPhotos = document.getElementById('input-photos');
    const previewGrid = document.getElementById('preview-grid');
    const photoCount  = document.getElementById('photo-count');

    inputPhotos.addEventListener('change', function () {
        const dispo = MAX_PHOTOS - nbExistant - filesValides.length;
        const selectionnes = Array.from(this.files);

        if (selectionnes.length > dispo) {
            alert(`Vous pouvez ajouter au maximum ${dispo} photo(s) supplémentaire(s) (limite : ${MAX_PHOTOS} au total).`);
        }

        const aAjouter = selectionnes.slice(0, dispo);
        aAjouter.forEach(file => {
            if (file.size > 5 * 1024 * 1024) { alert(`"${file.name}" dépasse 5 Mo, ignoré.`); return; }
            filesValides.push(file);
            ajouterPreview(file, filesValides.length - 1);
        });

        updateCount();
        rebuildInput();
        this.value = '';
    });

    // Drag & drop
    const zone = document.getElementById('upload-zone');
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor = '#c9a84c'; });
    zone.addEventListener('dragleave', () => { zone.style.borderColor = '#d0d0d0'; });
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.style.borderColor = '#d0d0d0';
        const fake = { files: e.dataTransfer.files };
        inputPhotos.dispatchEvent(Object.assign(new Event('change'), { target: fake }));
        // Déclencher manuellement
        const dispo = MAX_PHOTOS - nbExistant - filesValides.length;
        Array.from(e.dataTransfer.files).slice(0, dispo).forEach(file => {
            if (file.size > 5 * 1024 * 1024) { alert(`"${file.name}" dépasse 5 Mo, ignoré.`); return; }
            filesValides.push(file);
            ajouterPreview(file, filesValides.length - 1);
        });
        updateCount();
        rebuildInput();
    });

    function ajouterPreview(file, index) {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.dataset.index = index;
            div.innerHTML = `<img src="${e.target.result}" alt="">
            <button type="button" class="del-preview" onclick="supprimerPreview(${index}, this)">×</button>`;
            previewGrid.appendChild(div);
        };
        reader.readAsDataURL(file);
    }

    function supprimerPreview(index, btn) {
        filesValides[index] = null;
        btn.closest('.preview-item').remove();
        updateCount();
        rebuildInput();
    }

    function updateCount() {
        const nbNew = filesValides.filter(f => f !== null).length;
        photoCount.textContent = `(${nbExistant + nbNew}/${MAX_PHOTOS})`;
    }

    function rebuildInput() {
        // Recrée un DataTransfer avec les fichiers valides restants
        const dt = new DataTransfer();
        filesValides.filter(f => f !== null).forEach(f => dt.items.add(f));
        inputPhotos.files = dt.files;
    }

    /* ── Définir photo principale ────────────────────────────────────────────── */
    function setPrincipal(url, btn) {
        document.getElementById('image_principale_url').value = url;
        // Retirer tous les badges
        document.querySelectorAll('.principal-badge').forEach(b => {
            const parent = b.closest('.gallery-item');
            b.replaceWith(Object.assign(document.createElement('button'), {
                type: 'button', className: 'set-principal',
                textContent: 'Principale',
                onclick: function(){ setPrincipal(parent.querySelector('img').src, this); }
            }));
        });
        // Mettre le badge sur celle-ci
        const span = document.createElement('span');
        span.className = 'principal-badge'; span.textContent = 'Principale';
        btn.replaceWith(span);
    }

    /* ── Supprimer une image existante ───────────────────────────────────────── */
    function deleteImage(id, btn) {
        if (!confirm('Supprimer cette photo ?')) return;
        const item = btn.closest('.gallery-item');
        item.remove();
        nbExistant--;
        updateCount();
    }

    /* ── Masque date jj/mm/aaaa + validation ─────────────────────────────────── */
    document.querySelectorAll('.sf-date').forEach(input => {
        input.addEventListener('input', function () {
            let v   = this.value.replace(/\D/g, '').substring(0, 8);
            let out = '';
            if (v.length >= 1) out = v.substring(0, 2);
            if (v.length >= 3) out += '/' + v.substring(2, 4);
            if (v.length >= 5) out += '/' + v.substring(4, 8);
            this.value = out;
        });
        input.addEventListener('blur', function () {
            const val = this.value;
            if (!val) return;
            const parts = val.split('/');
            if (parts.length !== 3 || parts[2].length !== 4) { alert('Date incomplète. Format attendu : jj/mm/aaaa'); this.value = ''; return; }
            const j = parseInt(parts[0]), m = parseInt(parts[1]), a = parseInt(parts[2]);
            if (m < 1 || m > 12) { alert('Mois invalide.'); this.value = ''; return; }
            if (a > new Date().getFullYear()) { alert('Année future non autorisée.'); this.value = ''; return; }
            if (a < 1900) { alert('Année invalide.'); this.value = ''; return; }
            const maxJour = new Date(a, m, 0).getDate();
            if (j < 1 || j > maxJour) { alert(`Jour invalide pour ce mois (max ${maxJour}).`); this.value = ''; return; }
        });
    });

    document.getElementById('voiture-form').addEventListener('submit', function () {
        document.querySelectorAll('.sf-date').forEach(input => {
            const parts = input.value.split('/');
            if (parts.length === 3 && parts[2].length === 4)
                input.value = `${parts[2]}-${parts[1]}-${parts[0]}`;
        });
    });

    /* ── Modals ──────────────────────────────────────────────────────────────── */
    function openModal(type) {
        if (type === 'modele') {
            const v = document.getElementById('select-marque').value;
            if (v) document.getElementById('modal-modele-marque').value = v;
        }
        document.getElementById('modal-' + type).classList.add('open');
    }
    function closeModal(type) {
        document.getElementById('modal-' + type).classList.remove('open');
        if (type === 'marque') { document.getElementById('input-new-marque').value = ''; document.getElementById('modal-marque-error').style.display = 'none'; }
        else                   { document.getElementById('input-new-modele').value = ''; document.getElementById('modal-modele-error').style.display = 'none'; }
    }
    async function submitMarque() {
        const nom = document.getElementById('input-new-marque').value.trim();
        const err = document.getElementById('modal-marque-error');
        if (!nom) { err.textContent = 'Le nom est obligatoire.'; err.style.display = 'block'; return; }
        const fd = new FormData();
        fd.append('nom', nom);
        fd.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);
        const data = await (await fetch('/admin/marques/nouveau', { method: 'POST', body: fd })).json();
        if (data.error) { err.textContent = data.error; err.style.display = 'block'; return; }
        const sel = document.getElementById('select-marque');
        const opt = Object.assign(document.createElement('option'), { value: data.id, textContent: data.nom, selected: true });
        sel.appendChild(opt); sel.dispatchEvent(new Event('change'));
        const opt2 = Object.assign(document.createElement('option'), { value: data.id, textContent: data.nom });
        document.getElementById('modal-modele-marque').appendChild(opt2);
        closeModal('marque');
    }
    async function submitModele() {
        const marqueId = document.getElementById('modal-modele-marque').value;
        const nom      = document.getElementById('input-new-modele').value.trim();
        const err      = document.getElementById('modal-modele-error');
        if (!marqueId) { err.textContent = 'Choisissez une marque.'; err.style.display = 'block'; return; }
        if (!nom)      { err.textContent = 'Le nom est obligatoire.'; err.style.display = 'block'; return; }
        const fd = new FormData();
        fd.append('marque_id', marqueId); fd.append('nom', nom);
        fd.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);
        const data = await (await fetch('/admin/modeles/nouveau', { method: 'POST', body: fd })).json();
        if (data.error) { err.textContent = data.error; err.style.display = 'block'; return; }
        document.getElementById('select-marque').value = marqueId;
        await window._chargeModeles(marqueId, data.id);
        closeModal('modele');
    }
</script>
<script src="/assets/js/immat.js"></script>
</body>
</html>