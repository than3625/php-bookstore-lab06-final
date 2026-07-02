<?php
$q = $q ?? '';
$sort = $sort ?? 'created_at';
$direction = $direction ?? 'desc';
$books = $books ?? [];
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
?>

<h1>Book Management</h1>
<a class="btn primary" href="/books/create">+ Create Book</a>

<form method="get" action="/books" class="toolbar">
    <input type="hidden" name="page" value="1">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search title/author/ISBN">
    <input type="hidden" name="sort" value="<?= e($sort) ?>">
    <input type="hidden" name="direction" value="<?= e($direction) ?>">
    <button type="submit">Search</button>
</form>

<table>
<thead>
<tr>
    <th>ID</th>
    <th><a href="/books?<?= e(query_string(['sort' => 'title'])) ?>">Title</a></th>
    <th><a href="/books?<?= e(query_string(['sort' => 'author'])) ?>">Author</a></th>
    <th>ISBN</th>
    <th><a href="/books?<?= e(query_string(['sort' => 'price'])) ?>">Price</a></th>
    <th>Status</th>
    <th><a href="/books?<?= e(query_string(['sort' => 'created_at'])) ?>">Create at</a></th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php if (empty($books)): ?>
<tr>
    <td colspan="8" style="text-align: center; color: #888;">No books available.</td>
</tr>
<?php else: ?>
    <?php foreach ($books as $book): ?>
    <tr>
        <td><?= e($book['id']) ?></td>
        <td><strong><?= e($book['title']) ?></strong></td>
        <td><?= e($book['author']) ?></td>
        <td><code><?= e($book['isbn']) ?></code></td>
        <td><?= number_format((float)$book['price'], 0, ',', '.') ?> VND</td>
        <td>
            <?php if ($book['status'] === 'available'): ?>
                <span class="badge success">Available</span>
            <?php else: ?>
                <span class="badge danger">Out Of Stock</span>
            <?php endif; ?>
        </td>
        <td><?= e($book['created_at']) ?></td>
        <td>
            <a href="/books/edit?id=<?= e($book['id']) ?>" class="btn-edit">Edit</a>
            <form method="post" action="/books/delete" class="inline" onsubmit="return confirm('Delete this book?')">
                <input type="hidden" name="id" value="<?= e($book['id']) ?>">
                <button type="submit" class="link danger">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="/books?<?= e(query_string(['page' => $page - 1])) ?>">Prev</a>
    <?php endif; ?>
    <span>Page <?= e($page) ?> / <?= e($totalPages) ?></span>
    <?php if ($page < $totalPages): ?>
        <a href="/books?<?= e(query_string(['page' => $page + 1])) ?>">Next</a>
    <?php endif; ?>
</div>
