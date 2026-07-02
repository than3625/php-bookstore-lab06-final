<?php
class AuthService {
    public function __construct(private UserRepository $userRepo) {}

    public function login(array $data): array {
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $errors = [];

        if (empty($email)) {
            $errors['email'] = 'Email không được để trống.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không đúng định dạng.';
        }

        if (empty($password)) {
            $errors['password'] = 'Mật khẩu không được để trống.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $user = $this->userRepo->findActiveByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'errors' => ['general' => 'Email hoặc mật khẩu không đúng.']];
        }

        return ['success' => true, 'user' => $user];
    }
}