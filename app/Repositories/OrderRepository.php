<?php

class OrderRepository
{
    public function __construct(private PDO $db) {}

    public function countAll(string $keyword = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM orders";
        $params = [];

        if ($keyword !== '') {
            $sql .= "   WHERE order_code LIKE :keyword
                        OR customer_name LIKE :keyword
                        OR customer_email LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $direction): array
    {
        $allowedSorts = ['id', 'order_code', 'customer_name', 'customer_email', 'total_amount', 'status', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }
        if (!in_array(strtolower($direction), $allowedDirections, true)) {
            $direction = 'desc';
        }

        $sql = "SELECT id, order_code, customer_name, customer_email, total_amount, status, created_at
                FROM orders";
        $params = [];

        if ($keyword !== '') {
            $sql .= "   WHERE order_code LIKE :keyword
                        OR customer_name LIKE :keyword
                        OR customer_email LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }

        $sql .= " ORDER BY {$sort} {$direction}, id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO orders (order_code, customer_name, customer_email, total_amount, status)
                VALUES (:order_code, :customer_name, :customer_email, :total_amount, :status)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'order_code' => $data['order_code'],
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'] ?: null,
                'total_amount' => $data['total_amount'],
                'status' => $data['status'],
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Order code already exists.');
            }
            throw $e;
        }
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM orders WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $order = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $order ?: null;
    }

    public function update(int $id, array $data): bool
    {
        try {
            $sql = "UPDATE orders 
                    SET order_code = :order_code, 
                        customer_name = :customer_name, 
                        customer_email = :customer_email, 
                        total_amount = :total_amount, 
                        status = :status, 
                        updated_at = NOW() 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                'id' => $id,
                'order_code' => $data['order_code'],
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'total_amount' => $data['total_amount'],
                'status' => $data['status']
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000' || strpos($e->getMessage(), '1062') !== false) {
                throw new \DuplicateRecordException("Mã đơn hàng '" . $data['order_code'] . "' đã tồn tại trong hệ thống.");
            }
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM orders WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
