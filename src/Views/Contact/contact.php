<div class="section" style="max-width:640px;margin:0 auto">
  <div class="sec-label">Contactez-nous</div>
  <div class="sec-h2" style="margin-bottom:6px">Une question ? <em>Écrivez-nous</em></div>

  <?php if (!empty($success)): ?>
  <div style="background:#f0faf4;border-left:3px solid #27ae60;padding:14px 18px;margin-bottom:24px;font-size:10px;color:#1e8449">
    ✓ Votre message a bien été envoyé. Nous vous répondrons dans les meilleurs délais.
  </div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
  <div style="background:#fff5f5;border-left:3px solid #e74c3c;padding:12px 16px;margin-bottom:20px;font-size:10px;color:#c0392b">
    <?php foreach ($errors as $e): ?><div>• <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
  </div>
  <?php endif; ?>

  <form method="POST" action="/contact">
    <?= \Shared\CsrfGuard::field() ?>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
      <div class="sf">
        <div class="sf-lbl">Nom *</div>
        <input type="text" name="nom" class="sf-input" value="<?= htmlspecialchars($old['nom'] ?? '') ?>" required>
      </div>
      <div class="sf">
        <div class="sf-lbl">Email *</div>
        <input type="email" name="email" class="sf-input" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
      <div class="sf">
        <div class="sf-lbl">Téléphone</div>
        <input type="tel" name="telephone" class="sf-input" value="<?= htmlspecialchars($old['telephone'] ?? '') ?>">
      </div>
      <div class="sf">
        <div class="sf-lbl">Sujet</div>
        <input type="text" name="sujet" class="sf-input" value="<?= htmlspecialchars($old['sujet'] ?? '') ?>">
      </div>
    </div>
    <div class="sf" style="margin-bottom:22px">
      <div class="sf-lbl">Message *</div>
      <textarea name="message" class="sf-input" rows="5" style="resize:vertical" required><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn-gold" style="width:100%;justify-content:center;padding:13px">
      Envoyer le message
    </button>
  </form>

  <!-- Infos contact -->
  <div style="margin-top:40px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:1px;background:#e8e8e8">
    <?php $infos = [
      ['📍','Adresse','1279 Av. de Toulouse, 82000 Montauban'],
      ['📞','Téléphone','+33 (0)6 52 01 53 54'],
      ['🕐','Horaires','Lun – Ven · 09:00–12:00, 14:00–19:00
      Sam · 09:00–12:00, 14:00–18:00'],
    ]; ?>
    <?php foreach ($infos as [$ico,$lbl,$val]): ?>
    <div style="background:#fafafa;padding:20px 16px;text-align:center">
      <div style="font-size:18px;margin-bottom:7px"><?= $ico ?></div>
      <div style="font-size:7px;letter-spacing:.15em;text-transform:uppercase;color:#bbb;margin-bottom:4px"><?= $lbl ?></div>
      <div style="font-size:10px;color:#5a5a5a;line-height:1.5"><?= $val ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
