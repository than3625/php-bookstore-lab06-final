<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

require __DIR__ . '/../app/Core/helpers.php';
require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/Database.php';
require __DIR__ . '/../app/Core/DuplicateRecordException.php';

require __DIR__ . '/../app/Repositories/UserRepository.php';
require __DIR__ . '/../app/Repositories/BookRepository.php';
require __DIR__ . '/../app/Repositories/OrderRepository.php';

require __DIR__ . '/../app/Services/AuthService.php';
require __DIR__ . '/../app/Services/BookService.php';
require __DIR__ . '/../app/Services/OrderService.php';
require __DIR__ . '/../app/Services/DashboardService.php';

require __DIR__ . '/../app/Controllers/HomeController.php';
require __DIR__ . '/../app/Controllers/AuthController.php';
require __DIR__ . '/../app/Controllers/DashboardController.php';
require __DIR__ . '/../app/Controllers/BookController.php';
require __DIR__ . '/../app/Controllers/OrderController.php';
require __DIR__ . '/../app/Controllers/HealthController.php';

check_session_timeout();
check_session_context();

$routes = [
    'GET' => [
        '/' => [HomeController::class, 'index'],
        '/login' => [AuthController::class, 'login'],
        '/dashboard' => [DashboardController::class, 'index'],
        '/books' => [BookController::class, 'index'],
        '/books/create' => [BookController::class, 'create'],
        '/books/edit' => [BookController::class, 'edit'],
        '/orders' => [OrderController::class, 'index'],
        '/orders/create' => [OrderController::class, 'create'],
        '/orders/edit' => [OrderController::class, 'edit'],
        '/health' => [HealthController::class, 'index'],
    ],
    'POST' => [
        '/login' => [AuthController::class, 'handleLogin'],
        '/logout' => [AuthController::class, 'logout'],
        '/books/store' => [BookController::class, 'store'],
        '/books/update' => [BookController::class, 'update'],
        '/books/delete' => [BookController::class, 'delete'],
        '/orders/store' => [OrderController::class, 'store'],
        '/orders/update' => [OrderController::class, 'update'],
        '/orders/delete' => [OrderController::class, 'delete'],
    ],
];

$isProduction = false;

try {
    $router = new Router($routes);
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    error_log($e->getMessage());
    if ($isProduction === false) {
        throw $e;
    } else {
        http_response_code(500);
        require __DIR__ . '/../app/Views/errors/500.php';
    }
}