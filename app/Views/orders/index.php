<?php
$q = $q ?? '';
$sort = $sort ?? 'created_at';
$direction = $direction ?? 'desc';
$orders = $orders ?? [];
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
?>

<h1>Order Management</h1>

<div class="action-bar">
    <a class="btn primary" href="/orders/create">+ Create Order</a>
</div>

<form method="get" action="/orders" class="toolbar">
    <input type="hidden" name="page" value="1">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search code/name/email">
    <input type="hidden" name="sort" value="<?= e($sort) ?>">
    <input type="hidden" name="direction" value="<?= e($direction) ?>">
    <button type="submit">Search</button>
</form>

<table>
<thead>
<tr>
    <th>ID</th>
    <th><a href="/orders?<?= e(query_string(['sort' => 'order_code'])) ?>">Order Code</a></th>
    <th><a href="/orders?<?= e(query_string(['sort' => 'customer_name'])) ?>">Customer Name</a></th>
    <th>Customer Email</th>
    <th><a href="/orders?<?= e(query_string(['sort' => 'total_amount'])) ?>">Total Amount</a></th>
    <th>Status</th>
    <th><a href="/orders?<?= e(query_string(['sort' => 'created_at'])) ?>">Created at</a></th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php if (!empty($orders)): ?>
    <?php foreach ($orders as $order): ?>
    <tr>
        <td><?= e($order['id']) ?></td>
        <td><strong><?= e($order['order_code']) ?></strong></td>
        <td><?= e($order['customer_name']) ?></td>
        <td><?= e($order['customer_email']) ?></td>
        <td><?= e(number_format($order['total_amount'], 2)) ?></td>
        <td><span class="badge badge-<?= e($order['status']) ?>"><?= e($order['status']) ?></span></td>
        <td><?= e($order['created_at']) ?></td>
        <td>
            <a href="/orders/edit?id=<?= e($order['id']) ?>" class="btn-edit">Edit</a>
            <form method="post" action="/orders/delete" class="inline" onsubmit="return confirm('Delete this order?')">
                <input type="hidden" name="id" value="<?= e($order['id']) ?>">
                <button type="submit" class="link danger">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="8" style="text-align: center;">No orders found.</td>
    </tr>
<?php endif; ?>
</tbody>
</table>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="/orders?<?= e(query_string(['page' => $page - 1])) ?>">Prev</a>
    <?php endif; ?>
    <span>Page <?= e($page) ?> / <?= e($totalPages) ?></span>
    <?php if ($page < $totalPages): ?>
        <a href="/orders?<?= e(query_string(['page' => $page + 1])) ?>">Next</a>
    <?php endif; ?>
</div>

