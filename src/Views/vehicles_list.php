<?php
$vehicles = $vehicles ?? [];
?>


<h1>Vehicle Catalogue</h1>


<form method="get" action="/vehicles">
    <select name="type">
        <option value="">All</option>
        <option value="SUV">SUV</option>
        <option value="4x4">4x4</option>
        <option value="Sedan">Sedan</option>
    </select>
    <button type="submit">Filter</button>
</form>


<?php if (empty($vehicles)): ?>
    <p>No vehicles found.</p>
<?php else: ?>
    <?php foreach ($vehicles as $v): ?>
        <div style="border:1px solid #ddd;padding:10px;margin:10px 0;">
            <h3><?= htmlspecialchars($v['title'] ?? '') ?></h3>
            <p><?= htmlspecialchars($v['brand'] ?? '') ?> - <?= htmlspecialchars($v['type'] ?? '') ?></p>
            <p><?= number_format((float)($v['price'] ?? 0), 2, ',', ' ') ?> €</p>
            <a href="/vehicle?id=<?= (int)($v['id'] ?? 0) ?>">View details</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>



