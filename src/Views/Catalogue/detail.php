<?php $images = $images ?? []; ?>

    <div class="section" style="max-width:960px;margin:0 auto">

        <div style="margin-bottom:16px">
            <a href="/catalogue" style="font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);text-decoration:none">← Retour au catalogue</a>
        </div>

        <div style="display:grid;grid-template-columns:1fr 340px;gap:30px">

            <!-- PHOTO / GALERIE -->
            <div>
                <!-- Photo principale -->
                <div style="background:var(--g-ll);aspect-ratio:16/9;overflow:hidden;margin-bottom:12px;cursor:pointer" onclick="openLightbox(0)">
                    <?php $principale = $voiture['image_principale'] ?: ($images[0]['url'] ?? ''); ?>
                    <?php if ($principale): ?>
                        <img id="main-photo" src="<?= htmlspecialchars($principale) ?>" alt=""
                             style="width:100%;height:100%;object-fit:cover;transition:opacity .2s">
                    <?php else: ?>
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                            <svg width="200" height="80" viewBox="0 0 320 128" style="opacity:.2">
                                <path d="M32 88 L48 48 Q56 32 88 28 L208 26 Q240 26 264 42 L288 61 L296 88 Z" fill="#c9a84c"/>
                                <rect x="29" y="84" width="262" height="18" rx="4" fill="#c9a84c"/>
                                <circle cx="80"  cy="98" r="14" fill="#888"/>
                                <circle cx="240" cy="98" r="14" fill="#888"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Miniatures -->
                <?php if (count($images) > 1): ?>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px">
                        <?php foreach ($images as $i => $img): ?>
                            <div onclick="switchPhoto('<?= htmlspecialchars($img['url']) ?>', <?= $i ?>)"
                                 style="width:72px;height:50px;cursor:pointer;overflow:hidden;border:2px solid <?= $img['url'] === $principale ? 'var(--gold)' : 'transparent' ?>;flex-shrink:0"
                                 class="thumb" data-index="<?= $i ?>">
                                <img src="<?= htmlspecialchars($img['url']) ?>" alt=""
                                     style="width:100%;height:100%;object-fit:cover">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- SPECS TABLE -->
                <div style="border:1px solid var(--g-l)">
                    <div style="display:grid;grid-template-columns:1fr 1fr">
                        <?php
                        $specs = [
                                'Marque'        => $voiture['marque'],
                                'Modèle'        => $voiture['modele'],
                                'Année'         => $voiture['annee'],
                                'Kilométrage'   => number_format((int)$voiture['kilometrage'],0,',',' ').' km',
                                'Carburant'     => $voiture['carburant'],
                                'Transmission'  => $voiture['transmission'],
                                'Puissance'     => $voiture['puissance'] ? $voiture['puissance'].' ch' : '—',
                                'Couleur'       => $voiture['couleur']       ?: '—',
                                'Motorisation'  => $voiture['motorisation']  ?: '—',
                                'Finition'      => $voiture['finition']      ?: '—',
                                'Portes'        => $voiture['portes']        ?: '—',
                                'Places'        => $voiture['places']        ?: '—',
                        ];
                        if (!empty($voiture['date_mise_circulation'])) {
                            $specs['1ère circulation'] = date('d/m/Y', strtotime($voiture['date_mise_circulation']));
                        }
                        if (!empty($voiture['date_immatriculation'])) {
                            $specs['1ère immat.'] = date('d/m/Y', strtotime($voiture['date_immatriculation']));
                        }
                        ?>
                        <?php foreach ($specs as $label => $val): ?>
                            <div style="padding:10px 14px;border-bottom:1px solid var(--g-l);border-right:1px solid var(--g-l)">
                                <div style="font-size:7.5px;letter-spacing:.12em;text-transform:uppercase;color:#bbb;margin-bottom:3px"><?= $label ?></div>
                                <div style="font-size:12px;color:#3a3a3a"><?= htmlspecialchars((string)$val) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($voiture['description']): ?>
                    <div style="margin-top:22px">
                        <div style="font-size:8px;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);margin-bottom:10px">Description</div>
                        <p style="font-size:11px;color:#7a7a7a;line-height:1.85"><?= nl2br(htmlspecialchars($voiture['description'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- PANNEAU DROIT -->
            <div>
                <div style="border:1px solid var(--g-l);padding:24px">
                    <div style="font-size:8px;letter-spacing:.17em;text-transform:uppercase;color:var(--gold);margin-bottom:6px"><?= htmlspecialchars($voiture['marque']) ?></div>
                    <div style="font-family:var(--serif);font-size:26px;font-weight:300;color:#3a3a3a;line-height:1.15;margin-bottom:16px"><?= htmlspecialchars($voiture['modele']) ?></div>
                    <div style="border-top:1px solid var(--g-l);padding-top:16px;margin-bottom:20px">
                        <div style="font-size:8px;letter-spacing:.1em;text-transform:uppercase;color:#bbb;margin-bottom:5px">Prix de vente</div>
                        <div style="font-family:var(--serif);font-size:34px;font-weight:300;color:#3a3a3a"><?= number_format((float)$voiture['prix'],0,',',' ') ?> €</div>
                        <div style="font-size:9px;color:#aaa;margin-top:4px">Acompte de 10% via Stripe sécurisé</div>
                    </div>

                    <?php if ($voiture['statut'] === 'disponible'): ?>
                        <a href="/reservation?slug=<?= htmlspecialchars($voiture['slug']) ?>"
                           class="btn-gold" style="display:flex;justify-content:center;gap:8px;text-decoration:none;margin-bottom:10px">
                            Réserver ce véhicule
                        </a>
                    <?php else: ?>
                        <div style="background:var(--g-l);padding:13px;text-align:center;font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:#aaa">Véhicule non disponible</div>
                    <?php endif; ?>

                    <a href="/contact" class="btn-grey" style="display:flex;justify-content:center;text-decoration:none">Poser une question</a>

                    <div style="margin-top:20px;display:flex;flex-direction:column;gap:9px">
                        <?php foreach (['Inspection 150 points','Garantie 12 mois','Reprise possible','Financement disponible'] as $e): ?>
                            <div style="display:flex;align-items:center;gap:8px;font-size:9px;color:#8a8a8a">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2.5"><polyline points="20,6 9,17 4,12"/></svg>
                                <?= $e ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Lightbox ──────────────────────────────────────────────────────────── -->
<?php if (!empty($images)): ?>
    <div id="lightbox" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:2000;align-items:center;justify-content:center">
        <button onclick="closeLightbox()" style="position:absolute;top:20px;right:28px;background:none;border:none;color:#fff;font-size:32px;cursor:pointer;line-height:1">×</button>
        <button onclick="lbPrev()" style="position:absolute;left:20px;background:none;border:none;color:#fff;font-size:40px;cursor:pointer;line-height:1">‹</button>
        <img id="lb-img" src="" alt="" style="max-width:90vw;max-height:88vh;object-fit:contain">
        <div id="lb-counter" style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);color:rgba(255,255,255,.5);font-size:10px;letter-spacing:.1em"></div>
        <button onclick="lbNext()" style="position:absolute;right:20px;background:none;border:none;color:#fff;font-size:40px;cursor:pointer;line-height:1">›</button>
    </div>

    <script>
        const LB_IMAGES = <?= json_encode(array_values(array_column($images, 'url'))) ?>;
        let   lbIndex   = 0;

        function openLightbox(index) {
            lbIndex = index;
            lbShow();
            document.getElementById('lightbox').style.display = 'flex';
            document.addEventListener('keydown', lbKey);
        }
        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
            document.removeEventListener('keydown', lbKey);
        }
        function lbShow() {
            document.getElementById('lb-img').src       = LB_IMAGES[lbIndex];
            document.getElementById('lb-counter').textContent = (lbIndex + 1) + ' / ' + LB_IMAGES.length;
        }
        function lbPrev() { lbIndex = (lbIndex - 1 + LB_IMAGES.length) % LB_IMAGES.length; lbShow(); }
        function lbNext() { lbIndex = (lbIndex + 1) % LB_IMAGES.length; lbShow(); }
        function lbKey(e) {
            if (e.key === 'ArrowLeft')  lbPrev();
            if (e.key === 'ArrowRight') lbNext();
            if (e.key === 'Escape')     closeLightbox();
        }

        let activeThumb = 0;
        function switchPhoto(url, index) {
            document.getElementById('main-photo').src = url;
            document.querySelectorAll('.thumb').forEach((t, i) => {
                t.style.borderColor = i === index ? 'var(--gold)' : 'transparent';
            });
            activeThumb = index;
        }
    </script>
<?php endif; ?>