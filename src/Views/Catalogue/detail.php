<div class="section" style="max-width:960px;margin:0 auto">

  <div style="margin-bottom:16px">
    <a href="/catalogue" style="font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);text-decoration:none">← Retour au catalogue</a>
  </div>

  <div style="display:grid;grid-template-columns:1fr 340px;gap:30px">

    <!-- PHOTO / GALERIE -->
    <div>
      <div style="background:var(--g-ll);aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;margin-bottom:12px">
        <?php if ($voiture['image_principale']): ?>
          <img src="<?= htmlspecialchars($voiture['image_principale']) ?>" alt="" style="width:100%;height:100%;object-fit:cover">
        <?php else: ?>
          <svg width="200" height="80" viewBox="0 0 320 128" style="opacity:.2"><path d="M32 88 L48 48 Q56 32 88 28 L208 26 Q240 26 264 42 L288 61 L296 88 Z" fill="#c9a84c"/><rect x="29" y="84" width="268" height="22" rx="11" fill="#c9a84c" opacity=".4"/><circle cx="88" cy="103" r="19" fill="#bbb"/><circle cx="240" cy="103" r="19" fill="#bbb"/></svg>
        <?php endif; ?>
      </div>

      <!-- SPECS TABLE -->
      <div style="border:1px solid var(--g-l)">
        <div style="display:grid;grid-template-columns:1fr 1fr">
          <?php $specs = [
            'Marque'       => $voiture['marque'],
            'Modèle'       => $voiture['modele'],
            'Année'        => $voiture['annee'],
            'Kilométrage'  => number_format((int)$voiture['kilometrage'],0,',',' ').' km',
            'Carburant'    => $voiture['carburant'],
            'Transmission' => $voiture['transmission'],
            'Puissance'    => $voiture['puissance'] ? $voiture['puissance'].' ch' : '—',
            'Couleur'      => $voiture['couleur'] ?: '—',
            'Portes'       => $voiture['portes'],
            'Places'       => $voiture['places'],
          ]; ?>
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
          <a href="/reservation?slug=<?= htmlspecialchars($voiture['slug']) ?>" class="btn-gold" style="display:flex;justify-content:center;gap:8px;text-decoration:none;margin-bottom:10px">
            Réserver ce véhicule
          </a>
        <?php else: ?>
          <div style="background:var(--g-l);padding:13px;text-align:center;font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:#aaa">Véhicule non disponible</div>
        <?php endif; ?>
        <a href="/contact" class="btn-grey" style="display:flex;justify-content:center;text-decoration:none">Poser une question</a>

        <div style="margin-top:20px;display:flex;flex-direction:column;gap:9px">
          <?php $engagements = ['Inspection 150 points','Garantie 12 mois','Reprise possible','Financement disponible']; ?>
          <?php foreach ($engagements as $e): ?>
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
