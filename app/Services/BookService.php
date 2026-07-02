<?php

class BookService
{
    public function __construct(private BookRepository $repo) {}

    public function validate(array $input): array
    {
        if (!empty($input['website'])) {
            return ['values' => [], 'errors' => ['general' => 'Yêu cầu bị từ chối do phát hiên hành vi giống bot!']];
        }

        $lastTime = $_SESSION['last_book_create_time'] ?? 0;
        if (time() - $lastTime < 5) {
            return ['values' => [], 'errors' => ['general' => 'Bạn thao tác quá nhanh, vui lòng hãy đợi một chút!']];
        }
        
        $values = [
            'title'  => trim($input['title'] ?? ''),
            'author' => trim($input['author'] ?? ''),
            'isbn'   => trim($input['isbn'] ?? ''),
            'price'  => trim($input['price'] ?? ''),
            'status' => trim($input['status'] ?? 'available'),
        ];
        
        $errors = [];
        $allowedStatuses = ['available', 'out_of_stock'];

        if ($values['title'] === '') $errors['title'] = 'Vui lòng nhập tên tiêu đề sách.';
        if ($values['author'] === '') $errors['author'] = 'Vui lòng nhập tên tác giả.';
        if ($values['isbn'] === '') $errors['isbn'] = 'Vui lòng nhập mã ISBN.';
        if ($values['price'] === '' || !is_numeric($values['price']) || (float)$values['price'] < 0) {
            $errors['price'] = 'Giá tiền phải là một số và lớn hơn hoặc bằng 0.';
        }
        if (!in_array($values['status'], $allowedStatuses, true)) {
            $errors['status'] = 'Trạng thái sách không hợp lệ.';
        }

        return ['values' => $values, 'errors' => $errors];
    }

    public function getPaginated(string $q, int $page, int $perPage): array
    {
        $total = $this->repo->countAll($q);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $offset = ($page - 1) * $perPage;
        
        $books = $this->repo->getPaginated($q, $perPage, $offset, 'created_at', 'desc');

        return [
            'books' => $books,
            'total' => $total,
            'totalPages' => $totalPages,
            'page' => $page,
            'perPage' => $perPage
        ];
    }

    public function create(array $data): array
    {
        $validated = $this->validate($data);
        if (!empty($validated['errors'])) return $validated;

        try {
            $this->repo->create($validated['values']);
            $_SESSION['last_book_create_time'] = time();
            return ['success' => true];
        } catch (DuplicateRecordException $e) {
            return ['success' => false, 'errors' => ['isbn' => "Mã sách '{$validated['values']['isbn']}' đã tồn tại."]];
        }
    }

    public function update(int $id, array $data): array
    {
        $validated = $this->validate($data);
        if (!empty($validated['errors'])) return $validated;

        try {
            $validated['values']['id'] = $id;
            $this->repo->update($id, $validated['values']);
            return ['success' => true];
        } catch (DuplicateRecordException $e) {
            return ['success' => false, 'errors' => ['isbn' => "Mã sách '{$validated['values']['isbn']}' đã tồn tại."]];
        }
    }

    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }
}