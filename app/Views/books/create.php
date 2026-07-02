<h1>Create New Book</h1>

<form method="post" action="/books/store" class="card form-card">
    <?php if (!empty($errors['general'])): ?>
        <div class="alert error"><?= e($errors['general']) ?></div>
    <?php endif; ?>
    <label>Title</label>
    <input type="text" name="title" value="<?= e($old['title'] ?? '') ?>">
    <?php if (!empty($errors['title'])): ?><p class="error"><?= e($errors['title']) ?></p><?php endif; ?>

    <label>Author</label>
    <input type="text" name="author" value="<?= e($old['author'] ?? '') ?>">
    <?php if (!empty($errors['author'])): ?><p class="error"><?= e($errors['author']) ?></p><?php endif; ?>

    <label>ISBN</label>
    <input type="text" name="isbn" value="<?= e($old['isbn'] ?? '') ?>">
    <?php if (!empty($errors['isbn'])): ?><p class="error"><?= e($errors['isbn']) ?></p><?php endif; ?>

    <label>Price (VND)</label>
    <input type="number" name="price" value="<?= e($old['price'] ?? '') ?>" step="any">
    <?php if (!empty($errors['price'])): ?><p class="error"><?= e($errors['price']) ?></p><?php endif; ?>

    <label>Status</label>
    <select name="status">
        <option value="available" <?= ($old['status'] ?? 'available') === 'available' ? 'selected' : '' ?>>Available</option>
        <option value="out_of_stock" <?= ($old['status'] ?? 'available') === 'out_of_stock' ? 'selected' : '' ?>>Out of stock</option>
    </select>
    <?php if (!empty($errors['status'])): ?><p class="error"><?= e($errors['status']) ?></p><?php endif; ?>
    
    <div class="honeypot">
        <label>Website</label>
        <input name="website" tabindex="-1" autocomplete="off">
    </div>
    
    <button class="btn primary" type="submit">Save Book</button>
    <a class="btn" href="/books">Back</a>
</form>