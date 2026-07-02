<?php
class OrderService
{
    public function __construct(private OrderRepository $repo) {}

    public function validateOrder(array $input): array
    {
        if (!empty($input['website'])) {
            return ['values' => [], 'errors' => ['general' => 'Yêu cầu bị từ chối do phát hiên hành vi giống bot!']];
        }

        $lastTime = $_SESSION['last_order_create_time'] ?? 0;
        if (time() - $lastTime < 5) {
            return ['values' => [], 'errors' => ['general' => 'Bạn thao tác quá nhanh, vui lòng hãy đợi một chút!']];
        }

        $values = [
            'order_code'     => trim($input['order_code'] ?? ''),
            'customer_name'  => trim($input['customer_name'] ?? ''),
            'customer_email' => trim($input['customer_email'] ?? ''),
            'total_amount'   => (float)($input['total_amount'] ?? 0),
            'status'         => trim($input['status'] ?? 'pending'),
        ];

        $errors = [];
        $allowedStatuses = ['pending', 'paid', 'shipping', 'completed', 'cancelled'];

        if ($values['order_code'] === '') $errors['order_code'] = 'Vui lòng nhập mã đơn hàng.';
        if ($values['customer_name'] === '') $errors['customer_name'] = 'Vui lòng nhập tên khách hàng.';
        if ($values['customer_email'] !== '' && !filter_var($values['customer_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['customer_email'] = 'Email khách hàng không đúng định dạng.';
        }
        if ($values['total_amount'] < 0) $errors['total_amount'] = 'Tổng tiền không được âm.';
        if (!in_array($values['status'], $allowedStatuses, true)) $errors['status'] = 'Trạng thái không hợp lệ.';

        return ['values' => $values, 'errors' => $errors];
    }

    public function getPaginated(string $q, int $page, int $perPage): array
    {
        $total = $this->repo->countAll($q);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $offset = ($page - 1) * $perPage;
        
        $orders = $this->repo->getPaginated($q, $perPage, $offset, 'created_at', 'desc');

        return [
            'orders' => $orders,
            'total' => $total,
            'totalPages' => $totalPages,
            'page' => $page,
            'perPage' => $perPage
        ];
    }

    public function create(array $data): array
    {
        $validated = $this->validateOrder($data);
        if (!empty($validated['errors'])) return $validated;

        try {
            $this->repo->create($validated['values']);
            $_SESSION['last_order_create_time'] = time();
            return ['success' => true];
        } catch (DuplicateRecordException $e) {
            return ['success' => false, 'errors' => ['order_code' => 'Mã đơn hàng này đã tồn tại.']];
        }
    }

    public function update(int $id, array $data): array
    {
        if (!$this->repo->findById($id)) {
            return ['success' => false, 'errors' => ['general' => 'Đơn hàng không tồn tại.']];
        }

        $validated = $this->validateOrder($data);
        if (!empty($validated['errors'])) return $validated;

        try {
            $this->repo->update($id, $validated['values']);
            return ['success' => true];
        } catch (DuplicateRecordException $e) {
            return ['success' => false, 'errors' => ['order_code' => 'Mã đơn hàng này đã tồn tại.']];
        }
    }

    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }
}