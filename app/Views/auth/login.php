<?php 
$errorJson = flash_get('error');
$errors = $errorJson ? json_decode($errorJson, true) : []; 
?>
<h1>Đăng nhập hệ thống</h1>
<p>Tài khoản demo: <strong>admin@example.com</strong></p>
<p>Mật khẩu demo: <strong>123456</strong></p>
<form method="post" action="/login" class="card">
    <?php if (!empty($errors['general'])): ?>
        <div class="alert error"><?= e($errors['general']) ?></div>
    <?php endif; ?>
    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>">
        <?php if (!empty($errors['email'])): ?>
            <div class="error-text"><?= e($errors['email']) ?></div>
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label>Mật khẩu:</label>
        <input type="password" name="password">
        <?php if (!empty($errors['password'])): ?>
            <div class="error-text"><?= e($errors['password']) ?></div>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn primary">Đăng nhập</button>
</form>