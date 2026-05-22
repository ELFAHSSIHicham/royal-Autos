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

        <!-- Adresse -->
        <div style="background:#fafafa;padding:22px 16px;text-align:center">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5" width="18" height="18" style="margin-bottom:10px;display:block;margin-left:auto;margin-right:auto">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
            </svg>
            <div style="font-size:7px;letter-spacing:.15em;text-transform:uppercase;color:#bbb;margin-bottom:6px">Adresse</div>
            <div style="font-size:10px;color:#5a5a5a;line-height:1.7">
                1279 Av. de Toulouse<br>
                <span style="color:#aaa">82000 Montauban</span>
            </div>
        </div>

        <!-- Téléphone -->
        <div style="background:#fafafa;padding:22px 16px;text-align:center">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5" width="18" height="18" style="margin-bottom:10px;display:block;margin-left:auto;margin-right:auto">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.99 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.9 2.11h3a2 2 0 0 1 2 1.72c.128.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.572 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            <div style="font-size:7px;letter-spacing:.15em;text-transform:uppercase;color:#bbb;margin-bottom:6px">Téléphone</div>
            <div style="font-size:10px;color:#5a5a5a;line-height:1.7">
                <a href="tel:+33652015354" style="color:#5a5a5a;text-decoration:none">06 52 01 53 54</a>
            </div>
        </div>

        <!-- Horaires -->
        <div style="background:#fafafa;padding:22px 16px;text-align:center">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5" width="18" height="18" style="margin-bottom:10px;display:block;margin-left:auto;margin-right:auto">
                <circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>
            </svg>
            <div style="font-size:7px;letter-spacing:.15em;text-transform:uppercase;color:#bbb;margin-bottom:6px">Horaires</div>
            <div style="font-size:10px;color:#5a5a5a;line-height:1.9">
                Lun – Sam<br>
                <span style="color:#aaa">9h – 12h &nbsp;/&nbsp; 14h – 19h</span>
            </div>
        </div>

    </div>
</div>