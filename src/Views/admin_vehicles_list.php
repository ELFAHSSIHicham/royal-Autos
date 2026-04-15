<?php
$vehicles = $vehicles ?? [];
?>


<h1>Admin - Vehicles</h1>
<p><a href="/admin/vehicles/create">+ Add vehicle</a></p>


<?php if (empty($vehicles)): ?>
    <p>No vehicles yet.</p>
<?php else: ?>
    <?php foreach ($vehicles as $v): ?>
        <div style="border:1px solid #ddd;padding:10px;margin:10px 0;">
            <strong><?= htmlspecialchars($v['title'] ?? '') ?></strong>
            - <?= htmlspecialchars($v['brand'] ?? '') ?>
            - <?= number_format((float)($v['price'] ?? 0), 2, ',', ' ') ?> €
        </div>
    <?php endforeach; ?>
<?php endif; ?>


