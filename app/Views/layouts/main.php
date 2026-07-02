<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= e($title ?? 'Secure Mini CRM') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/nav.php'; ?>
    <main class="container">
        <?php require __DIR__ . '/../partials/flash.php'; ?>
        <?= $content ?? '' ?>
    </main>
</body>
</html>