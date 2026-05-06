<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Administration — Royal Autos</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="ra" style="display:flex;align-items:center;justify-content:center;min-height:100vh;background:#d3d3d3">
  <div style="background:#fafafa;width:360px;border-top:3px solid #c9a84c;padding:36px 32px">
    <div style="text-align:center;margin-bottom:28px">
      <div style="font-family:'Cormorant Garamond',serif;font-size:20px;letter-spacing:.14em;color:#3a3a3a">ROYAL AUTOS</div>
      <div style="font-size:7.5px;letter-spacing:.2em;text-transform:uppercase;color:#bbb;margin-top:3px">Administration</div>
    </div>

    <?php if (!empty($error)): ?>
    <div style="background:#fff5f5;border-left:3px solid #e74c3c;padding:10px 14px;margin-bottom:18px;font-size:10px;color:#c0392b">
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="/admin/login">
      <?= \Shared\CsrfGuard::field() ?>
      <div class="sf" style="margin-bottom:14px">
        <div class="sf-lbl">Email</div>
        <input type="email" name="email" class="sf-input" required autofocus>
      </div>
      <div class="sf" style="margin-bottom:22px">
        <div class="sf-lbl">Mot de passe</div>
        <input type="password" name="password" class="sf-input" required>
      </div>
      <button type="submit" class="btn-gold" style="width:100%;justify-content:center;padding:12px">
        Se connecter
      </button>
    </form>
  </div>
</body>
</html>
