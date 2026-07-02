<?php

class AuthController
{
    public function login(): void
    {
        try{
            if (is_logged_in()) {
                redirect('/dashboard');
            }

            render('auth/login', [
                'title' => 'Đăng nhập',
                'old' => json_decode(flash_get('old') ?? '{}', true),
                'errors' => json_decode(flash_get('errors') ?? '{}', true)
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }

    public function handleLogin(): void
    {
        try{
            $db = Database::connect(require __DIR__ . '/../../config/database.php');
            $userRepo = new UserRepository($db);
            $authService = new AuthService($userRepo);
            $result = $authService->login($_POST);

            if (!$result['success']) {
                flash_set('error', json_encode($result['errors']));
                flash_set('old', json_encode(['email' => $_POST['email'] ?? '']));
                redirect('/login');
            }
            $user = $result['user'];
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['login_at'] = time();
            $_SESSION['last_activity_at'] = time();
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';

            flash_set('success', 'Đăng nhập thành công!');
            redirect('/dashboard');
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }

    public function logout(): void
    {
        logout_clean();
        session_start();
        session_regenerate_id(true);
        flash_set('success', 'Đăng xuất thành công. Session cũ đã được xóa.');
        redirect('/login');
    }
}