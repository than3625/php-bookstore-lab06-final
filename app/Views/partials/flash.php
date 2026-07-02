<?php if ($success = flash_get('success')): ?>
    <div class="alert success"><?= e($success) ?></div>
<?php endif; ?>

<?php if ($error = flash_get('error')): ?>
    <div class="alert error"><?= e($error) ?></div>
<?php endif; ?>