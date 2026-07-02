<?php

class BookRepository
{
    public function __construct(private PDO $db) {}

    public function countAll(string $keyword = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM books";
        $params = [];

        if ($keyword !== '') {
            $sql .= "   WHERE title LIKE :k1
                        OR author LIKE :k2
                        OR isbn LIKE :k3";
            
            $value = '%' . $keyword . '%';
            $params['k1'] = $value;
            $params['k2'] = $value;
            $params['k3'] = $value;
        }
    

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $direction): array
    {
        $allowedSorts = ['id', 'title', 'author', 'isbn', 'price', 'status', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }
        if (!in_array(strtolower($direction), $allowedDirections, true)) {
            $direction = 'desc';
        }

        $sql = "SELECT id, title, author, isbn, price, status, created_at FROM books";
        $params = [];

        if ($keyword !== '') {
            $sql .= "   WHERE title LIKE :k1
                        OR author LIKE :k2
                        OR isbn LIKE :k3";
            
            $value = '%' . $keyword . '%';
            $params['k1'] = $value;
            $params['k2'] = $value;
            $params['k3'] = $value;
        }

        $sql .= " ORDER BY {$sort} {$direction}, id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO books (title, author, isbn, price, status)
                VALUES (:title, :author, :isbn, :price, :status)";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'title'=>$data['title'],
                'author'=>$data['author'],
                'isbn'=>$data['isbn'],
                'price'=>$data['price'],
                'status'=>$data['status']
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('ISBN already exists.');
            }
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE books
                SET title=:title, author=:author, isbn=:isbn, price=:price, status=:status
                WHERE id=:id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'title' => $data['title'],
                'author' => $data['author'],
                'isbn'=>$data['isbn'],
                'price'=>$data['price'],
                'status'=>$data['status']
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('ISBN already exists.');
            }
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM books WHERE id=:id");
        return $stmt->execute(['id'=>$id]);
    }

    public function getCountByStatus(string $status): int 
    {
        $sql = "SELECT COUNT(*) FROM books WHERE status = :status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status]);
        return (int) $stmt->fetchColumn();
    }

    public function getByStatus(string $status): array 
    {
        return $this->db->query("SELECT * FROM books WHERE status = '$status'")->fetchAll();
    }
}