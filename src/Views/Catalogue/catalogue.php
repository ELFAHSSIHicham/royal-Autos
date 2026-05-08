<div class="section" style="min-height:60vh">
    <div class="sec-head">
        <div>
            <div class="sec-label">Catalogue</div>
            <div class="sec-h2"><?= $total ?? 0 ?> <em>véhicules disponibles</em></div>
        </div>
    </div>

    <!-- FILTRES -->
    <form method="GET" action="/catalogue" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px">
        <input type="text"   name="search"    class="sf-input"   placeholder="Recherche…" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" style="flex:1;min-width:160px">
        <select name="marque_id" class="sf-select" style="min-width:140px">
            <option value="">Toutes marques</option>
            <?php foreach ($marques as $m): ?>
                <option value="<?= (int)$m['id'] ?>" <?= ((string)($filters['marque_id'] ?? '')) === (string)$m['id'] ? 'selected' : '' ?>><?= htmlspecialchars($m['nom']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="prix_max"  class="sf-input"   placeholder="Budget max €"    value="<?= htmlspecialchars($filters['prix_max'] ?? '') ?>" style="width:130px">
        <select name="carburant" class="sf-select" style="min-width:120px">
            <option value="">Carburant</option>
            <?php foreach (['Essence','Diesel','Hybride','Électrique'] as $c): ?>
                <option value="<?= $c ?>" <?= ($filters['carburant'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-gold" style="padding:9px 20px">Filtrer</button>
        <a href="/catalogue" class="btn-grey" style="padding:9px 16px;display:inline-flex;align-items:center">Réinitialiser</a>
    </form>

    <!-- GRILLE -->
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
                            <svg width="130" height="55" viewBox="0 0 320 128" style="opacity:.18"><path d="M32 88 L48 48 Q56 32 88 28 L208 26 Q240 26 264 42 L288 61 L296 88 Z" fill="#c9a84c"/><rect x="29" y="84" width="268" height="22" rx="11" fill="#c9a84c" opacity=".4"/><circle cx="88" cy="103" r="19" fill="#bbb"/><circle cx="240" cy="103" r="19" fill="#bbb"/></svg>
                        <?php endif; ?>
                        <?php if ($v['est_vedette']): ?><span class="card-badge">Coup de cœur</span><?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="card-marque"><?= htmlspecialchars($v['marque']) ?></div>
                        <div class="card-nom"><?= htmlspecialchars($v['modele']) ?></div>
                        <div class="card-specs">
                            <span><?= (int)$v['annee'] ?></span><span>·</span>
                            <span><?= number_format((int)$v['kilometrage'],0,',',' ') ?> km</span><span>·</span>
                            <span><?= htmlspecialchars($v['carburant']) ?></span>
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
                    <a href="/catalogue?<?= $q ?>" style="padding:7px 13px;border:1px solid <?= $i === $page ? 'var(--gold)' : 'var(--g-d)' ?>;font-size:11px;color:<?= $i === $page ? 'var(--gold)' : '#aaa' ?>;text-decoration:none"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>