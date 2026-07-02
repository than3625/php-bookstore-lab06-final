<h1>Edit Order Details</h1>

<form method="post" action="/orders/update" class="card form-card">
    
    <input type="hidden" name="id" value="<?= e($old['id'] ?? '') ?>">

    <label>Order Code</label>
    <input type="text" name="order_code" value="<?= e($old['order_code'] ?? '') ?>" readonly style="background-color: #f1f1f1; cursor: not-allowed;">
    <?php if (!empty($errors['order_code'])): ?><p class="error"><?= e($errors['order_code']) ?></p><?php endif; ?>

    <label>Customer Name</label>
    <input type="text" name="customer_name" value="<?= e($old['customer_name'] ?? '') ?>">
    <?php if (!empty($errors['customer_name'])): ?><p class="error"><?= e($errors['customer_name']) ?></p><?php endif; ?>
    
    <label>Customer Email</label>
    <input type="email" name="customer_email" value="<?= e($old['customer_email'] ?? '') ?>">
    <?php if (!empty($errors['customer_email'])): ?><p class="error"><?= e($errors['customer_email']) ?></p><?php endif; ?>
    
    <label>Total Amount (đ)</label>
    <input type="number" name="total_amount" value="<?= e($old['total_amount'] ?? '') ?>" step="any">
    <?php if (!empty($errors['total_amount'])): ?><p class="error"><?= e($errors['total_amount']) ?></p><?php endif; ?>

    <label>Order Status</label>
    <select name="status">
        <?php foreach (['pending', 'processing', 'completed', 'cancelled'] as $status): ?>
            <option value="<?= e($status) ?>" <?= ($old['status'] ?? 'pending') === $status ? 'selected' : '' ?>>
                <?= e(ucfirst($status)) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($errors['status'])): ?><p class="error"><?= e($errors['status']) ?></p><?php endif; ?>

    <label>Order Note</label>
    <textarea name="note" rows="4"><?= e($old['note'] ?? '') ?></textarea>
    <?php if (!empty($errors['note'])): ?><p class="error"><?= e($errors['note']) ?></p><?php endif; ?>

    <button class="btn primary" type="submit">Update Order</button>
    <a class="btn" href="/orders">Back</a>
</form>