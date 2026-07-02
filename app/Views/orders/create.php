<?php
$errors = $errors ?? [];
$old = $old ?? ['order_code' => '', 'customer_name' => '', 'customer_email' => '', 'total_amount' => 0, 'status' => 'pending'];
?>

<div class="form-container">
    <h1>Create New Order</h1>
    <a href="/orders" class="btn-back">← Back to List</a>
    <form method="post" action="/orders/store" class="card form-card">
        <?php if (!empty($errors['general'])): ?>
            <div class="alert error"><?= e($errors['general']) ?></div>
        <?php endif; ?>
        <div class="form-group">
            <label for="order_code">Order Code <span class="required">*</span></label>
            <input type="text" id="order_code" name="order_code" value="<?= e($old['order_code']) ?>" class="<?= isset($errors['order_code']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['order_code'])): ?>
                <span class="error message"><?= e($errors['order_code']) ?></span>
            <?php endif; ?>
        </div>


        <div class="form-group">
            <label for="customer_name">Customer Name <span class="required">*</span></label>
            <input type="text" id="customer_name" name="customer_name" value="<?= e($old['customer_name']) ?>" class="<?= isset($errors['customer_name']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['customer_name'])): ?>
                <span class="error message"><?= e($errors['customer_name']) ?></span>
            <?php endif; ?>
        </div>


        <div class="form-group">
            <label for="customer_email">Customer Email</label>
            <input type="text" id="customer_email" name="customer_email" value="<?= e($old['customer_email']) ?>" class="<?= isset($errors['customer_email']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['customer_email'])): ?>
                <span class="error message"><?= e($errors['customer_email']) ?></span>
            <?php endif; ?>
        </div>


        <div class="form-group">
            <label for="total_amount">Total Amount (VND)</label>
            <input type="number" step="0.01" id="total_amount" name="total_amount" value="<?= e($old['total_amount']) ?>" class="<?= isset($errors['total_amount']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['total_amount'])): ?>
                <span class="error message"><?= e($errors['total_amount']) ?></span>
            <?php endif; ?>
        </div>


        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="pending" <?= $old['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="processing" <?= $old['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                <option value="shipping" <?= $old['status'] === 'shipping' ? 'selected' : '' ?>>Shipping</option>
                <option value="completed" <?= $old['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= $old['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>

        <div class="honeypot">
            <label>Website</label>
            <input name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn primary">Save Order</button>
            <a href="/orders" class="btn text">Cancel</a>
        </div>

    </form>
</div>