<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Message reçu — Royal Autos</title></head>
<body style="font-family:'Jost',Arial,sans-serif;background:#f2f2f2;margin:0;padding:20px">
  <div style="max-width:540px;margin:0 auto;background:#fff;border-top:3px solid #c9a84c">
    <div style="padding:28px 32px 0">
      <div style="font-family:'Cormorant Garamond',Georgia,serif;font-size:22px;color:#3a3a3a;letter-spacing:.1em">ROYAL AUTOS</div>
      <div style="font-size:8px;letter-spacing:.2em;color:#bbb;text-transform:uppercase;margin-bottom:24px">Automobiles de Prestige · Montauban</div>
    </div>
    <div style="padding:0 32px 32px">
      <p style="font-size:13px;color:#3a3a3a">Bonjour <?= htmlspecialchars($nom ?? '') ?>,</p>
      <p style="font-size:11px;color:#7a7a7a;line-height:1.85;margin:14px 0">Nous avons bien reçu votre message et nous vous répondrons dans les meilleurs délais (sous 24h, du lundi au samedi).</p>
      <div style="background:#f8f8f8;border-left:3px solid #c9a84c;padding:14px 18px;margin:20px 0;font-size:10px;color:#7a7a7a;line-height:1.7">
        <?= nl2br(htmlspecialchars($message ?? '')) ?>
      </div>
      <p style="font-size:10px;color:#aaa">L'équipe Royal Autos · 15 Av. de la République, Montauban · +33 (0)5 63 00 00 00</p>
    </div>
  </div>
</body>
</html>
