<div class="section" style="min-height:60vh;padding:30px 20px">

    <div class="sec-head" style="margin-bottom:20px">
        <div>
            <div class="sec-label">Catalogue</div>
            <div class="sec-h2"><?= $total ?? 0 ?> <em>véhicule<?= ($total ?? 0) > 1 ? 's' : '' ?> disponible<?= ($total ?? 0) > 1 ? 's' : '' ?></em></div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:260px 1fr;gap:24px;align-items:start">

        <!-- ── SIDEBAR FILTRES ── -->
        <form method="GET" action="/catalogue" id="filter-form">

            <!-- Recherche -->
            <div style="margin-bottom:6px">
                <div style="position:relative">
                    <input type="text" name="search" class="sf-input"
                           placeholder="Recherche libre…"
                           value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                           style="padding-left:30px">
                    <svg style="position:absolute;left:9px;top:50%;transform:translateY(-50%);pointer-events:none" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#bbb" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </div>
            </div>

            <!-- Marque -->
            <div class="filter-block">
                <div class="filter-title">Marque</div>
                <select name="marque_id" class="sf-select" id="cat-marque" onchange="loadModeles(this.value)">
                    <option value="">Toutes les marques</option>
                    <?php foreach ($marques as $m): ?>
                        <option value="<?= (int)$m['id'] ?>" <?= ((string)($filters['marque_id'] ?? '')) === (string)$m['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Modèle -->
            <div class="filter-block" id="filter-modele" style="<?= empty($filters['marque_id']) ? 'opacity:.4;pointer-events:none' : '' ?>">
                <div class="filter-title">Modèle</div>
                <select name="modele_id" class="sf-select" id="cat-modele">
                    <option value="">Tous les modèles</option>
                </select>
            </div>

            <!-- Énergie -->
            <div class="filter-block">
                <div class="filter-title">Énergie</div>
                <div style="display:flex;flex-direction:column;gap:6px">
                    <?php foreach (['Essence','Diesel','Hybride','Électrique','GPL'] as $c): ?>
                        <label style="display:flex;align-items:center;gap:8px;font-size:11px;color:#7a7a7a;cursor:pointer">
                            <input type="radio" name="carburant" value="<?= $c ?>"
                                    <?= ($filters['carburant'] ?? '') === $c ? 'checked' : '' ?>
                                   style="accent-color:var(--gold)">
                            <?= $c ?>
                        </label>
                    <?php endforeach; ?>
                    <label style="display:flex;align-items:center;gap:8px;font-size:11px;color:#bbb;cursor:pointer">
                        <input type="radio" name="carburant" value=""
                                <?= empty($filters['carburant']) ? 'checked' : '' ?>
                               style="accent-color:var(--gold)">
                        Tous
                    </label>
                </div>
            </div>

            <!-- Boîte de vitesses -->
            <div class="filter-block">
                <div class="filter-title">Boîte de vitesses</div>
                <div style="display:flex;flex-direction:column;gap:6px">
                    <?php foreach (['Automatique','Manuelle'] as $t): ?>
                        <label style="display:flex;align-items:center;gap:8px;font-size:11px;color:#7a7a7a;cursor:pointer">
                            <input type="radio" name="transmission" value="<?= $t ?>"
                                    <?= ($filters['transmission'] ?? '') === $t ? 'checked' : '' ?>
                                   style="accent-color:var(--gold)">
                            <?= $t ?>
                        </label>
                    <?php endforeach; ?>
                    <label style="display:flex;align-items:center;gap:8px;font-size:11px;color:#bbb;cursor:pointer">
                        <input type="radio" name="transmission" value=""
                                <?= empty($filters['transmission']) ? 'checked' : '' ?>
                               style="accent-color:var(--gold)">
                        Toutes
                    </label>
                </div>
            </div>

            <!-- Budget -->
            <div class="filter-block">
                <div class="filter-title">Budget (€)</div>
                <div style="display:flex;gap:8px;align-items:center">
                    <input type="number" name="prix_min" class="sf-input" placeholder="Min"
                           value="<?= htmlspecialchars($filters['prix_min'] ?? '') ?>" style="width:50%">
                    <span style="color:#bbb;font-size:11px">–</span>
                    <input type="number" name="prix_max" class="sf-input" placeholder="Max"
                           value="<?= htmlspecialchars($filters['prix_max'] ?? '') ?>" style="width:50%">
                </div>
            </div>

            <!-- Année -->
            <div class="filter-block">
                <div class="filter-title">Année</div>
                <div style="display:flex;gap:8px;align-items:center">
                    <input type="number" name="annee_min" class="sf-input" placeholder="De"
                           value="<?= htmlspecialchars($filters['annee_min'] ?? '') ?>"
                           min="1990" max="<?= date('Y') ?>" style="width:50%">
                    <span style="color:#bbb;font-size:11px">–</span>
                    <input type="number" name="annee_max" class="sf-input" placeholder="À"
                           value="<?= htmlspecialchars($filters['annee_max'] ?? '') ?>"
                           min="1990" max="<?= date('Y') ?>" style="width:50%">
                </div>
            </div>

            <!-- Kilométrage -->
            <div class="filter-block">
                <div class="filter-title">Kilométrage max (km)</div>
                <select name="km_max" class="sf-select">
                    <option value="">Sans limite</option>
                    <?php foreach ([10000,30000,50000,80000,100000,150000,200000] as $km): ?>
                        <option value="<?= $km ?>" <?= ((string)($filters['km_max'] ?? '')) === (string)$km ? 'selected' : '' ?>>
                            <?= number_format($km, 0, ',', ' ') ?> km
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Bouton avec badge compteur -->
            <div style="position:relative;margin-bottom:8px">
                <button type="submit" class="btn-gold" id="btn-search" style="width:100%;justify-content:center;padding-right:50px">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Voir les résultats
                </button>
                <span id="btn-count-badge"><?= $total ?? 0 ?></span>
            </div>
            <a href="/catalogue" class="btn-grey" style="width:100%;justify-content:center;font-size:9px;letter-spacing:.1em;text-transform:uppercase">
                Réinitialiser
            </a>

        </form>

        <!-- ── GRILLE VÉHICULES ── -->
        <div>
            <?php if (empty($voitures)): ?>
                <div style="text-align:center;padding:60px 0;color:#aaa">
                    <p style="font-family:var(--serif);font-size:22px">Aucun véhicule trouvé</p>
                    <p style="font-size:11px;margin-top:8px">Modifiez vos filtres ou <a href="/contact" style="color:var(--gold)">contactez-nous</a>.</p>
                </div>
            <?php else: ?>
                <div class="cars">
                    <?php foreach ($voitures as $v): ?>
                        <div class="card" onclick="window.location='/voiture/<?= htmlspecialchars($v['slug']) ?>'">
                            <div class="card-img">
                                <?php if ($v['image_principale']): ?>
                                    <img src="<?= htmlspecialchars($v['image_principale']) ?>" alt="" style="width:100%;height:100%;object-fit:cover">
                                <?php else: ?>
                                    <svg width="130" height="55" viewBox="0 0 320 128" style="opacity:.18">
                                        <path d="M32 88 L48 48 Q56 32 88 28 L208 26 Q240 26 264 42 L288 61 L296 88 Z" fill="#c9a84c"/>
                                        <rect x="29" y="84" width="268" height="22" rx="11" fill="#c9a84c" opacity=".4"/>
                                        <circle cx="88" cy="103" r="19" fill="#bbb"/>
                                        <circle cx="240" cy="103" r="19" fill="#bbb"/>
                                    </svg>
                                <?php endif; ?>
                                <?php if ($v['est_vedette']): ?><span class="card-badge">Coup de cœur</span><?php endif; ?>
                            </div>
                            <div class="card-body">
                                <div class="card-marque"><?= htmlspecialchars($v['marque']) ?></div>
                                <div class="card-nom"><?= htmlspecialchars($v['modele']) ?></div>
                                <div class="card-specs">
                                    <span><?= (int)$v['annee'] ?></span><span>·</span>
                                    <span><?= number_format((int)$v['kilometrage'],0,',',' ') ?> km</span><span>·</span>
                                    <span><?= htmlspecialchars($v['carburant']) ?></span><span>·</span>
                                    <span><?= htmlspecialchars($v['transmission']) ?></span>
                                </div>
                                <div class="card-footer">
                                    <div><div class="card-price-lbl">Prix de vente</div><div class="card-price"><?= number_format((float)$v['prix'],0,',',' ') ?> €</div></div>
                                    <div class="card-arrow">→</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- PAGINATION -->
                <?php if ($pages > 1): ?>
                    <div style="display:flex;justify-content:center;gap:6px;margin-top:28px">
                        <?php for ($i = 1; $i <= $pages; $i++): ?>
                            <?php $q = http_build_query(array_merge($filters, ['page' => $i])); ?>
                            <a href="/catalogue?<?= $q ?>"
                               style="padding:7px 13px;border:1px solid <?= $i === $page ? 'var(--gold)' : 'var(--g-d)' ?>;font-size:11px;color:<?= $i === $page ? 'var(--gold)' : '#aaa' ?>;text-decoration:none;transition:border-color .2s">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .filter-block       { border-top:1px solid var(--g-l); padding:14px 0; }
    .filter-title       { font-size:8px; letter-spacing:.18em; text-transform:uppercase; color:#aaa; margin-bottom:10px; }
    #btn-search         { transition: opacity .2s; }
    #btn-search.loading { opacity: .6; pointer-events: none; }

    #btn-count-badge {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        background: #fff;
        color: var(--gold);
        font-size: 11px;
        font-weight: 500;
        min-width: 26px;
        height: 26px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 7px;
        box-shadow: 0 1px 4px rgba(0,0,0,.15);
    }
    #btn-count-badge.pulse {
        animation: badge-pulse .3s ease;
    }
    @keyframes badge-pulse {
        0%   { transform: translateY(-50%) scale(1); }
        50%  { transform: translateY(-50%) scale(1.25); }
        100% { transform: translateY(-50%) scale(1); }
    }

    @media (max-width: 860px) {
        .section > div[style*="grid-template-columns:260px"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<script>
    const SELECTED_MODELE = <?= json_encode((string)($filters['modele_id'] ?? '')) ?>;
    let countTimer = null;

    /* ── Chargement modèles dynamique ── */
    function loadModeles(marqueId) {
        const sel   = document.getElementById('cat-modele');
        const block = document.getElementById('filter-modele');

        if (!marqueId) {
            sel.innerHTML             = '<option value="">Tous les modèles</option>';
            block.style.opacity       = '.4';
            block.style.pointerEvents = 'none';
            /* Mise à jour immédiate du compteur quand on remet "toutes les marques" */
            debouncedCount();
            return;
        }

        block.style.opacity       = '1';
        block.style.pointerEvents = 'auto';

        fetch('/api/modeles?marque_id=' + marqueId)
            .then(r => r.json())
            .then(data => {
                sel.innerHTML = '<option value="">Tous les modèles</option>';
                data.forEach(m => {
                    const opt = document.createElement('option');
                    opt.value       = m.id;
                    opt.textContent = m.nom;
                    if (String(m.id) === SELECTED_MODELE) opt.selected = true;
                    sel.appendChild(opt);
                });
                /* Mise à jour du compteur après chargement des modèles */
                debouncedCount();
            });
    }

    /* ── Compteur live avec badge ── */
    function updateCount() {
        const form  = document.getElementById('filter-form');
        const btn   = document.getElementById('btn-search');
        const badge = document.getElementById('btn-count-badge');
        const params = new URLSearchParams();
        for (const [k, v] of new FormData(form).entries()) {
            if (v) params.append(k, v);
        }
        btn.classList.add('loading');
        fetch('/api/count?' + params.toString())
            .then(r => r.json())
            .then(json => {
                const n = json.count ?? 0;
                badge.textContent = n;
                badge.classList.remove('pulse');
                void badge.offsetWidth;
                badge.classList.add('pulse');
                btn.classList.remove('loading');
            })
            .catch(() => btn.classList.remove('loading'));
    }

    function debouncedCount() {
        clearTimeout(countTimer);
        countTimer = setTimeout(updateCount, 350);
    }

    /* ── Init ── */
    document.addEventListener('DOMContentLoaded', () => {
        const marqueInit = document.getElementById('cat-marque').value;
        if (marqueInit) loadModeles(marqueInit);

        document.getElementById('filter-form').querySelectorAll('input, select').forEach(el => {
            el.addEventListener('change', debouncedCount);
            if (el.type === 'text' || el.type === 'number') {
                el.addEventListener('input', debouncedCount);
            }
        });
    });
</script>