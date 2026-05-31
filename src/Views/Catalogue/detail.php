<?php
$images  = $images  ?? [];
$voiture = $voiture ?? [];
?>

    <style>
        /* ── Styles impression ─────────────────────────────────────── */
        @media print {
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

            body, html { background: #fff !important; color: #1a1a1a !important; }
            body.dark * { background: #fff !important; color: #1a1a1a !important; border-color: #e0e0e0 !important; }

            .no-print, .lightbox-wrap, #lightbox { display: none !important; }

            .print-only { display: block !important; }

            .print-header {
                display: flex !important;
                align-items: center;
                justify-content: space-between;
                border-bottom: 2px solid #c9a84c;
                padding-bottom: 16px;
                margin-bottom: 28px;
            }
            .print-logo-name  { font-size: 22px; font-family: 'Cormorant Garamond', serif; letter-spacing: .12em; color: #1a1a1a; }
            .print-logo-sub   { font-size: 9px; letter-spacing: .15em; text-transform: uppercase; color: #c9a84c; margin-top: 4px; }
            .print-contact    { text-align: right; font-size: 9px; color: #666; line-height: 1.8; }

            .detail-grid { grid-template-columns: 1fr 260px !important; gap: 20px !important; }
            .detail-grid > div:first-child { order: 2; }
            .detail-grid > div:last-child  { order: 1; }

            #main-photo { max-height: 260px !important; }
            .thumb-strip { display: none !important; }

            .print-panel {
                border: 1px solid #e0e0e0 !important;
                background: #fafafa !important;
            }

            .print-footer {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                border-top: 1px solid #e0e0e0;
                margin-top: 28px;
                padding-top: 12px;
                font-size: 8px;
                color: #aaa;
                letter-spacing: .08em;
            }
        }
        .print-only   { display: none; }
        .print-footer { display: none; }
        .print-header { display: none; }
    </style>

    <!-- EN-TÊTE PDF (visible uniquement à l'impression) -->
    <div class="print-header">
        <div>
            <div class="print-logo-name">ROYAL AUTOS</div>
            <div class="print-logo-sub">Automobiles de Prestige · Montauban</div>
        </div>
        <div class="print-contact">
            1279 Avenue de Toulouse, 82000 Montauban<br>
            Tél : 06 52 01 53 54 &nbsp;·&nbsp; royalauto@laposte.net<br>
            Lun – Sam &nbsp;·&nbsp; 9h–12h / 14h–19h
        </div>
    </div>

    <div class="section" style="max-width:960px;margin:0 auto">

        <div style="margin-bottom:16px" class="no-print">
            <a href="/catalogue" style="font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);text-decoration:none">← Retour au catalogue</a>
        </div>

        <div class="detail-grid" style="display:grid;grid-template-columns:1fr 340px;gap:30px">

            <!-- PHOTO / GALERIE -->
            <div>
                <div style="background:var(--g-ll);aspect-ratio:16/9;overflow:hidden;margin-bottom:12px;cursor:pointer"
                     onclick="openLightbox(0)" class="no-print">
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

                <!-- Photo pour impression uniquement -->
                <?php if (!empty($principale)): ?>
                    <div class="print-only" style="margin-bottom:16px">
                        <img src="<?= htmlspecialchars($principale) ?>" alt=""
                             style="width:100%;max-height:280px;object-fit:cover;border:1px solid #e0e0e0">
                    </div>
                <?php endif; ?>

                <!-- Miniatures -->
                <?php if (count($images) > 1): ?>
                    <div class="thumb-strip no-print" style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px">
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
                                'Marque'        => $voiture['marque']       ?? '—',
                                'Modèle'        => $voiture['modele']       ?? '—',
                                'Année'         => $voiture['annee']        ?? '—',
                                'Kilométrage'   => number_format((int)($voiture['kilometrage'] ?? 0),0,',',' ').' km',
                                'Carburant'     => $voiture['carburant']    ?? '—',
                                'Transmission'  => $voiture['transmission'] ?? '—',
                                'Puissance'     => !empty($voiture['puissance']) ? $voiture['puissance'].' ch' : '—',
                                'Couleur'       => $voiture['couleur']      ?: '—',
                                'Motorisation'  => $voiture['motorisation'] ?: '—',
                                'Finition'      => $voiture['finition']     ?: '—',
                                'Portes'        => $voiture['portes']       ?: '—',
                                'Places'        => $voiture['places']       ?: '—',
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

                <?php if (!empty($voiture['description'])): ?>
                    <div style="margin-top:22px">
                        <div style="font-size:8px;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);margin-bottom:10px">Description</div>
                        <p style="font-size:11px;color:#7a7a7a;line-height:1.85"><?= nl2br(htmlspecialchars($voiture['description'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- PANNEAU DROIT -->
            <div>
                <div class="print-panel" style="border:1px solid var(--g-l);padding:24px">
                    <div style="font-size:8px;letter-spacing:.17em;text-transform:uppercase;color:var(--gold);margin-bottom:6px"><?= htmlspecialchars($voiture['marque'] ?? '') ?></div>
                    <div style="font-family:var(--serif);font-size:26px;font-weight:300;color:#3a3a3a;line-height:1.15;margin-bottom:16px"><?= htmlspecialchars($voiture['modele'] ?? '') ?></div>

                    <div style="border-top:1px solid var(--g-l);padding-top:16px;margin-bottom:20px">
                        <div style="font-size:8px;letter-spacing:.1em;text-transform:uppercase;color:#bbb;margin-bottom:5px">Prix de vente</div>
                        <div style="font-family:var(--serif);font-size:34px;font-weight:300;color:#3a3a3a"><?= number_format((float)($voiture['prix'] ?? 0),0,',',' ') ?> €</div>
                        <div class="no-print" style="font-size:9px;color:#aaa;margin-top:4px">Acompte de 10% via Stripe sécurisé</div>
                    </div>

                    <?php if (($voiture['statut'] ?? '') === 'disponible'): ?>
                        <a href="/reservation?slug=<?= htmlspecialchars($voiture['slug'] ?? '') ?>"
                           class="btn-gold no-print" style="display:flex;justify-content:center;gap:8px;text-decoration:none;margin-bottom:10px">
                            Réserver ce véhicule
                        </a>
                    <?php else: ?>
                        <div class="no-print" style="background:var(--g-l);padding:13px;text-align:center;font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:#aaa">Véhicule non disponible</div>
                    <?php endif; ?>

                    <!-- Bouton imprimer fiche -->
                    <button onclick="window.print()"
                            class="no-print"
                            style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px;background:none;border:1px solid var(--g-l);cursor:pointer;font-family:inherit;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#7a7a7a;margin-bottom:10px;transition:border-color .2s,color .2s"
                            onmouseover="this.style.borderColor='var(--gold)';this.style.color='var(--gold)'"
                            onmouseout="this.style.borderColor='var(--g-l)';this.style.color='#7a7a7a'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 6,2 18,2 18,9"/>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                            <rect x="6" y="14" width="12" height="8"/>
                        </svg>
                        Imprimer la fiche
                    </button>

                    <a href="/contact" class="btn-grey no-print" style="display:flex;justify-content:center;text-decoration:none">Poser une question</a>

                    <div class="no-print" style="margin-top:20px;display:flex;flex-direction:column;gap:9px">
                        <?php foreach (['Paiement sécurisé en ligne','Photos vérifiées','Contact direct avec le garage'] as $e): ?>
                            <div style="display:flex;align-items:center;gap:8px;font-size:9px;color:#8a8a8a">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2.5"><polyline points="20,6 9,17 4,12"/></svg>
                                <?= $e ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Infos impression uniquement -->
                    <div class="print-only" style="margin-top:16px;font-size:9px;color:#888;line-height:1.9;border-top:1px solid #e0e0e0;padding-top:12px">
                        <div>Tél : <strong>06 52 01 53 54</strong></div>
                        <div>Email : royalauto@laposte.net</div>
                        <div>1279 Avenue de Toulouse, 82000 Montauban</div>
                        <div style="margin-top:8px;color:#c9a84c;font-size:8px;letter-spacing:.1em;text-transform:uppercase">Prix indicatif — sous réserve de disponibilité</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de fiche PDF -->
    <div class="print-footer">
        <span>Royal Autos Montauban — royalauto@laposte.net — 06 52 01 53 54</span>
        <span>Fiche éditée le <?= date('d/m/Y') ?> — Prix indicatif, sous réserve de disponibilité</span>
    </div>

    <!-- ── Lightbox ──────────────────────────────────────────────────────────── -->
<?php if (!empty($images)): ?>
    <div id="lightbox" class="lightbox-wrap no-print"
         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:2000;align-items:center;justify-content:center">
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
            document.getElementById('lb-img').src = LB_IMAGES[lbIndex];
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