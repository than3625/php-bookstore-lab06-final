<?php

class UserRepository {
    public function __construct(private PDO $db) {}

    public function findActiveByEmail(string $email): ?array 
    {
        $sql = "SELECT * FROM users WHERE email = :email AND status = 'active' LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }
}