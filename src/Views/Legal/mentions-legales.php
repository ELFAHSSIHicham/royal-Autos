<div class="section" style="max-width:720px;margin:0 auto">
  <div class="sec-h2" style="margin-bottom:24px">Mentions <em>légales</em></div>

  <?php $sections = [
    ['Éditeur du site', 'Royal Autos Montauban — 1279 Avenue de Toulouse, 82000 Montauban.<br>Tél : +33 (0)6 52 01 53 54
 · Email : royalauto@laposte.net'],
    ['Hébergement', 'Hébergeur : à compléter — adresse, téléphone.'],
    ['Propriété intellectuelle', 'L\'ensemble du contenu de ce site (textes, images, graphismes) est protégé par le droit d\'auteur. Toute reproduction est interdite sans autorisation préalable.'],
    ['Données personnelles', 'Les données collectées via les formulaires sont uniquement utilisées pour répondre à vos demandes et ne sont jamais cédées à des tiers. Conformément au RGPD, vous pouvez exercer vos droits en écrivant à royalauto@laposte.net.'],
    ['Cookies', 'Ce site utilise uniquement des cookies techniques nécessaires à son fonctionnement. Aucun cookie publicitaire n\'est déposé.'],
  ]; ?>

  <?php foreach ($sections as [$title, $content]): ?>
  <div style="margin-bottom:28px">
    <div style="font-family:'Cormorant Garamond',serif;font-size:18px;color:#3a3a3a;margin-bottom:8px;border-bottom:1px solid #e8e8e8;padding-bottom:6px"><?= $title ?></div>
    <p style="font-size:11px;color:#7a7a7a;line-height:1.85"><?= $content ?></p>
  </div>
  <?php endforeach; ?>
</div>
