<?php if (!isset($vehicle) || !$vehicle): ?>
    <h1>Vehicle not found</h1>
    <p><a href="/vehicles">Back to catalogue</a></p>
    <?php return; ?>
<?php endif; ?>


<h1><?= htmlspecialchars($vehicle['title'] ?? '') ?></h1>
<p>Brand: <?= htmlspecialchars($vehicle['brand'] ?? '') ?></p>
<p>Type: <?= htmlspecialchars($vehicle['type'] ?? '') ?></p>
<p>Price: <?= number_format((float)($vehicle['price'] ?? 0), 2, ',', ' ') ?> €</p>
<p>Mileage: <?= (int)($vehicle['mileage'] ?? 0) ?> km</p>
<p>Year: <?= (int)($vehicle['year'] ?? 0) ?></p>
<p><?= nl2br(htmlspecialchars($vehicle['description'] ?? '')) ?></p>


<p><a href="/vehicles">Back to catalogue</a></p>


