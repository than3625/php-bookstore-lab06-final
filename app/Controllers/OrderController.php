<?php

class OrderController
{
    private function getService(): OrderService {
        $db = Database::connect(require __DIR__ . '/../../config/database.php');
        return new OrderService(new OrderRepository($db));
    }

    public function index(): void {
        try {
            $q = trim($_GET['q'] ?? '');
            $page = max(1, (int) ($_GET['page'] ?? 1));
            $data = $this->getService()->getPaginated($q, $page, 10);
        
            render('orders/index', array_merge($data, [
                'q' => $q, 
                'title' => 'Quản Lý Đơn Hàng',
                'sort' => $_GET['sort'] ?? 'created_at',
                'direction' => $_GET['direction'] ?? 'desc'
            ]));
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }

    public function create(): void { 
        try{
            render('orders/create', [
                'errors' => [], 
                'old' => [], 
                'title' => 'Create Order'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }

    public function store(): void {
        try {
            $result = $this->getService()->create($_POST);
            if (isset($result['errors'])) {
                render('orders/create', [
                    'errors' => $result['errors'], 
                    'old' => $_POST,
                    'title' => 'Create Order'
                ]);
                return;
            }
            flash_set('success', 'Đơn hàng đã được tạo thành công.');
            redirect('/orders');
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }

    public function edit(): void {
        try {
            $id = max(0, (int)($_GET['id'] ?? 0));
            $db = Database::connect(require __DIR__ . '/../../config/database.php');
            $order = (new OrderRepository($db))->findById($id);
            if (!$order) {
                http_response_code(404);
                render('errors/404', ['title' => 'Page Not Found']);
                return;
            }
            render('orders/edit', [
                'errors' => [], 
                'old' => $order,
                'title' => 'Chỉnh Sửa Đơn Hàng'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }

    public function update(): void {
        try{
            $id = max(0, (int)($_POST['id'] ?? 0));
            $result = $this->getService()->update($id, $_POST);
            if (isset($result['errors'])) {
                $old = $_POST; $old['id'] = $id;
                render('orders/edit', [
                    'errors' => $result['errors'], 
                    'old' => $old,
                    'title' => 'Edit Order'
                ]);
                return;
            }
            flash_set('success', 'Cập nhật đơn hàng thành công.');
            redirect('/orders');
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }

    public function delete(): void {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                render('errors/405', ['title' => 'Method Not Allowed']);
                return;
            }
            $this->getService()->delete((int)($_POST['id'] ?? 0));
            flash_set('success', 'Đã xóa đơn hàng thành công.');
            redirect('/orders');
        } catch (Exception $e) {
                http_response_code(500);
                render('errors/500', ['title' => 'Server Error']);
        }
    }
}