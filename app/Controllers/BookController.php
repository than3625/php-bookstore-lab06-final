<?php

class BookController
{
    private function getService(): BookService
    {
        $db = Database::connect(require __DIR__ . '/../../config/database.php');
        return new BookService(new BookRepository($db));
    }

    public function index(): void
    {
        try{
            $q = trim($_GET['q'] ?? '');
            $page = max(1, (int) ($_GET['page'] ?? 1));
            $data = $this->getService()->getPaginated($q, $page, 10);

            render('books/index', array_merge($data, [
            'q' => $q, 
            'title' => 'Quản Lý Kho Sách',
            'sort' => $_GET['sort'] ?? 'created_at',
            'direction' => $_GET['direction'] ?? 'desc'
            ]));
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }

    public function create(): void
    {
        try {
            render('books/create', ['errors' => [], 'old' => [], 'title' => 'Create Book']);
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }

    public function store(): void
    {
        try {
            $result = $this->getService()->create($_POST);
            
            if (isset($result['errors'])) {
                render('books/create', ['errors' => $result['errors'], 'old' => $_POST, 'title' => 'Create Book']);
                return;
            }
            
            flash_set('success', 'Sách đã được thêm thành công.');
            redirect('/books');
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }

    public function edit(): void
    {
        try {
            $id = max(0, (int)($_GET['id'] ?? 0));
            $db = Database::connect(require __DIR__ . '/../../config/database.php');
            $book = (new BookRepository($db))->findById($id);

            if (!$book) {
                http_response_code(404);
                render('errors/404', ['title' => 'Page Not Found']);
                return;
            }

            render('books/edit', ['errors' => [], 'old' => $book, 'title' => 'Edit Book']);
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }

    public function update(): void
    {
        try {
            $id = (int)($_POST['id'] ?? 0);
            $result = $this->getService()->update($id, $_POST);
            
            if (isset($result['errors'])) {
                $old = $_POST;
                $old['id'] = $id;
                render('books/edit', ['errors' => $result['errors'], 'old' => $old, 'title' => 'Edit Book']);
                return;
            }
            
            flash_set('success', 'Cập nhật sách thành công.');
            redirect('/books');
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }

    public function delete(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                render('errors/405', ['title' => 'Method Not Allowed']);
                return;
            }

            $id = (int)($_POST['id'] ?? 0);
            $db = Database::connect(require __DIR__ . '/../../config/database.php');
            (new BookRepository($db))->delete($id);

            flash_set('success', 'Đã xóa sách thành công.');
            redirect('/books');
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }
}