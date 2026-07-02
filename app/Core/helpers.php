<?php 

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

function render(string $view, array $data = [], string $layout = 'layouts/main'): void
{
    extract($data);
    ob_start();
    require __DIR__ . '/../Views/' . $view . '.php';
    $content = ob_get_clean();
    require __DIR__ . '/../Views/' . $layout . '.php';
}

function query_string(array $params = []): string
{
    $current = $_GET;
    foreach ($params as $key => $value) {
        $current[$key] = $value;
    }
    return http_build_query($current);
}

function flash_set(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string
{
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()){
        flash_set('error', 'Vui lòng đăng nhập để truy cập dashboard.');
        redirect('/login');
    }
}

function check_session_timeout(): void
{
    $idleLimit = 15*60; 

    if(!isset($_SESSION['user_id'])){
        return;
    }

    $last = $_SESSION['last_activity_at'] ?? time();
    if (time() - $last > $idleLimit){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['role']);

        flash_set('error', 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại');
        redirect('/login');
    }

    $_SESSION['last_activity_at'] = time();
}

function check_session_context(): void
{
    if (!isset($_SESSION['user_id'])) {
        return;
    }

    $currentAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $savedAgent = $_SESSION['user_agent'] ?? '';

    if($savedAgent && $savedAgent !== $currentAgent) {
        logout_clean();
        session_start();
        flash_set('error', 'Phiên đăng nhập không hợp lệ. Vui lòng đăng nhập lại.');
        redirect('/login');
    }
}

function logout_clean(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')){
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}
