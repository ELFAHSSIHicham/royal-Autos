<div class="section" style="max-width:700px;margin:0 auto">
  <div style="margin-bottom:16px"><a href="/voiture/<?= htmlspecialchars($voiture['slug']) ?>" style="font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);text-decoration:none">← Retour au véhicule</a></div>

  <div class="sec-h2" style="margin-bottom:4px">Réserver <em><?= htmlspecialchars($voiture['marque'].' '.$voiture['modele']) ?></em></div>
  <p style="font-size:11px;color:#aaa;margin-bottom:28px">Un acompte de <strong><?= number_format($voiture['prix'] * 0.1,0,',',' ') ?> €</strong> (10%) sera demandé via Stripe pour confirmer votre réservation.</p>

  <?php if (!empty($errors)): ?>
  <div style="background:#fff5f5;border-left:3px solid #e74c3c;padding:12px 16px;margin-bottom:20px;font-size:10px;color:#c0392b">
    <?php foreach ($errors as $e): ?><div>• <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
  </div>
  <?php endif; ?>

  <form method="POST" action="/reservation">
    <?= \Shared\CsrfGuard::field() ?>
    <input type="hidden" name="slug" value="<?= htmlspecialchars($voiture['slug']) ?>">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
      <div class="sf"><div class="sf-lbl">Nom *</div><input type="text" name="nom"    class="sf-input" value="<?= htmlspecialchars($old['nom']    ?? '') ?>" required></div>
      <div class="sf"><div class="sf-lbl">Prénom</div><input type="text" name="prenom" class="sf-input" value="<?= htmlspecialchars($old['prenom'] ?? '') ?>"></div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
      <div class="sf"><div class="sf-lbl">Email *</div><input type="email" name="email" class="sf-input" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required></div>
      <div class="sf"><div class="sf-lbl">Téléphone *</div><input type="tel" name="telephone" class="sf-input" value="<?= htmlspecialchars($old['telephone'] ?? '') ?>" required></div>
    </div>
    <div class="sf" style="margin-bottom:22px">
      <div class="sf-lbl">Message / Questions</div>
      <textarea name="notes" class="sf-input" rows="4" style="resize:vertical"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
    </div>

    <button type="submit" class="btn-gold" style="width:100%;justify-content:center;padding:14px">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      Confirmer et payer l'acompte (<?= number_format($voiture['prix'] * 0.1,0,',',' ') ?> €)
    </button>
    <p style="font-size:8.5px;color:#bbb;text-align:center;margin-top:10px">Paiement 100% sécurisé par Stripe. Vous serez redirigé(e) sur la page de paiement.</p>
  </form>
</div>
