<?php $stats = $stats ?? []; ?>
<h1>Dashboard</h1>
<div class="card">
    <h2>Xin chào, <?= e($_SESSION['user_name'] ?? '') ?></h2>
    <p>Role: <strong><?= e($_SESSION['user_role'] ?? '') ?></strong></p>
    <p>Login at: <?= e(date('Y-m-d H:i:s', $_SESSION['login_at'] ?? time())) ?></p>
    <p>Last activity: <?= e(date('Y-m-d H:i:s', $_SESSION['last_activity_at'] ?? time())) ?></p>
</div>
<div class="card">
    <h3>Sách Available: <?= $stats['available_count'] ?></h3>
    <h3>Sách Sold Out: <?= $stats['sold_out_count'] ?></h3>
    <h3>Tổng Order: <?= $stats['total_orders'] ?></h3>
</div>
<div class="row">
    <div class="col">
        <h4>Sách Available (<?= $stats['available_count'] ?>)</h4>
        <ul>
            <?php foreach ($stats['available_books'] as $book): ?>
                <li><?= e($book['title']) ?> - <?= e($book['price']) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="col">
        <h4>Sách Sold Out (<?= $stats['sold_out_count'] ?>)</h4>
        <ul style="color: red;">
            <?php foreach ($stats['sold_out_books'] as $book): ?>
                <li><?= e($book['title']) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
