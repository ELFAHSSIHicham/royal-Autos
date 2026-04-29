<?php /* Template : page d'accueil Royal Autos */ ?>

<!-- HERO -->
<div class="hero">
    <div class="hero-main">

        <!-- PHOTO DE FOND (remplace hero-texture + hero-car) -->
        <div class="hero-bg">
             <img src="/assets/img/hero1.jpg" alt="Royal Autos — Véhicules de prestige">
             <div class="hero-overlay"></div>
         </div>

         <div class="hero-content">
             <div>
                 <div class="eyebrow">
                     <div class="eyebrow-line"></div>
                     <span class="eyebrow-txt">Montauban · Tarn-et-Garonne · Depuis 2008</span>
                 </div>
                 <h1 class="h1">Le meilleur<br>de l'automobile<br><em>à votre portée</em></h1>
                 <p class="hero-desc">Chaque véhicule est rigoureusement sélectionné, inspecté et garanti. Une expérience d'acquisition à la hauteur de vos exigences.</p>
                 <div class="hero-btns">
                     <a href="/catalogue" class="btn-gold">
                         <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                         Explorer la collection
                     </a>
                     <a href="/contact" class="btn-grey">Nous contacter</a>
                 </div>
             </div>
         </div>

     </div>

     <!-- SIDEBAR RECHERCHE -->
    <div class="hero-side">
        <div class="side-top">
            <div class="side-title">Trouver un véhicule</div>
            <div class="side-sub">Affinez votre recherche</div>
            <form method="GET" action="/catalogue">
                <div class="sf">
                    <div class="sf-lbl">Marque</div>
                    <select name="marque_id" id="select-marque" class="sf-select">
                        <option value="">Toutes les marques</option>
                        <?php foreach ($marques ?? [] as $m): ?>
                            <option value="<?= (int)$m['id'] ?>"><?= htmlspecialchars($m['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="sf" id="sf-modele" style="display:none">
                    <div class="sf-lbl">Modèle</div>
                    <select name="modele_id" id="select-modele" class="sf-select">
                        <option value="">Tous les modèles</option>
                    </select>
                </div>
                <div class="sf">
                    <div class="sf-lbl">Budget maximum</div>
                    <input type="number" name="prix_max" class="sf-input" placeholder="100 000 €">
                </div>
                <div class="sf">
                    <div class="sf-lbl">Carburant</div>
                    <select name="carburant" class="sf-select">
                        <option value="">Tous carburants</option>
                        <option>Essence</option>
                        <option>Diesel</option>
                        <option>Hybride</option>
                        <option>Électrique</option>
                    </select>
                </div>
                <div class="sf">
                    <div class="sf-lbl">Kilométrage max</div>
                    <select name="km_max" class="sf-select">
                        <option value="">Sans limite</option>
                        <option value="30000">– de 30 000 km</option>
                        <option value="60000">– de 60 000 km</option>
                        <option value="100000">– de 100 000 km</option>
                    </select>
                </div>
                <div class="sf">
                    <div class="sf-lbl">Année minimum</div>
                    <select name="annee_min" class="sf-select">
                        <option value="">Toutes années</option>
                        <?php for ($y = date('Y'); $y >= 2015; $y--): ?>
                            <option value="<?= $y ?>"><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="search-btn">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Lancer la recherche
                </button>
            </form>
        </div>
        <div class="side-bottom">
            <div class="ss-stat"><div class="ss-num"><?= $nbVoitures ?? '–' ?></div><div class="ss-lbl">En stock</div></div>
            <div class="ss-stat"><div class="ss-num">7</div><div class="ss-lbl">Marques</div></div>
            <div class="ss-stat"><div class="ss-num">12m</div><div class="ss-lbl">Garantie</div></div>
        </div>
    </div>
</div>


<!-- BRANDS STRIP -->
<div class="strip">
    <div style="display:flex;gap:18px;align-items:center">
        <?php foreach (['Mercedes-Benz','BMW','Audi','Porsche','Tesla','Volvo','Volkswagen','Renault','Peugeot','Ford','Seat'] as $i => $b): ?>
            <?php if ($i > 0): ?><span style="color:var(--gold);opacity:.35;font-size:7px">◆</span><?php endif; ?>
            <span class="brand"><?= $b ?></span>
        <?php endforeach; ?>
    </div>
</div>


<!-- SÉLECTION VEDETTES -->
<div class="section">
    <div class="sec-head">
        <div>
            <div class="sec-label">Collection printemps <?= date('Y') ?></div>
            <div class="sec-h2">Véhicules <em>sélectionnés</em></div>
        </div>
        <a href="/catalogue" class="sec-link">Voir toute la collection →</a>
    </div>
    <?php if (!empty($vedettes)): ?>
        <div class="cars">
            <?php foreach ($vedettes as $v): ?>
                <div class="card" onclick="window.location='/voiture/<?= htmlspecialchars($v['slug']) ?>'">
                    <div class="card-img">
                        <?php if (!empty($v['image_principale'])): ?>
                            <img src="<?= htmlspecialchars($v['image_principale']) ?>" alt="<?= htmlspecialchars($v['marque'].' '.$v['modele']) ?>" style="width:100%;height:100%;object-fit:cover">
                        <?php else: ?>
                            <svg width="150" height="60" viewBox="0 0 320 128" style="opacity:.2">
                                <path d="M32 88 L48 48 Q56 32 88 28 L208 26 Q240 26 264 42 L288 61 L296 88 Z" fill="#c9a84c"/>
                                <rect x="29" y="84" width="268" height="22" rx="11" fill="#c9a84c" opacity=".4"/>
                                <circle cx="88"  cy="103" r="19" fill="#bbb"/>
                                <circle cx="240" cy="103" r="19" fill="#bbb"/>
                            </svg>
                        <?php endif; ?>
                        <span class="card-badge">Sélection</span>
                    </div>
                    <div class="card-body">
                        <div class="card-marque"><?= htmlspecialchars($v['marque']) ?></div>
                        <div class="card-nom"><?= htmlspecialchars($v['modele']) ?></div>
                        <div class="card-specs">
                            <span><?= (int)$v['annee'] ?></span><span>·</span>
                            <span><?= number_format((int)$v['kilometrage'], 0, ',', ' ') ?> km</span><span>·</span>
                            <span><?= htmlspecialchars($v['carburant']) ?></span>
                        </div>
                        <div class="card-footer">
                            <div>
                                <div class="card-price-lbl">Prix de vente</div>
                                <div class="card-price"><?= number_format((float)$v['prix'], 0, ',', ' ') ?> €</div>
                            </div>
                            <div class="card-arrow">→</div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


<!-- ENGAGEMENTS -->
<div class="eng">
    <div class="eng-grid">
        <div>
            <div class="eng-label">Notre promesse</div>
            <div class="eng-h2">La qualité<br>comme <em>standard</em></div>
            <div class="eng-p">Depuis 2008, chaque véhicule est sélectionné avec la même exigence. Parce qu'un achat automobile mérite une confiance absolue.</div>
        </div>
        <div class="eng-items">
            <div class="ei"><div class="ei-num">01</div><div class="ei-title">Véhicules certifiés</div><div class="ei-desc">Inspection complète 150 points. Historique fourni à l'acheteur.</div></div>
            <div class="ei"><div class="ei-num">02</div><div class="ei-title">Garantie 12 mois</div><div class="ei-desc">Garantie mécanique complète 12 mois sans franchise.</div></div>
            <div class="ei"><div class="ei-num">03</div><div class="ei-title">Financement sur-mesure</div><div class="ei-desc">Solutions adaptées avec nos partenaires bancaires. Réponse en 24h.</div></div>
            <div class="ei"><div class="ei-num">04</div><div class="ei-title">Livraison à domicile</div><div class="ei-desc">Possible dans un rayon de 200 km autour de Montauban.</div></div>
        </div>
    </div>
</div>


<!-- CTA -->
<div class="cta">
    <div>
        <div class="cta-label">Vous ne trouvez pas votre bonheur ?</div>
        <div class="cta-h3">Confiez-nous votre <em>recherche</em>,<br>nous trouvons le véhicule qu'il vous faut.</div>
    </div>
    <div class="cta-btns">
        <a href="/contact" class="btn-cta-main">Déposer une demande</a>
    </div>
</div>