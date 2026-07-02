<?php

class DashboardController
{
    public function index(): void
    {
        require_login(); 

        try{
            $config = require __DIR__ . '/../../config/database.php';
            $db = Database::connect($config);
            
            $bookRepo = new BookRepository($db);
            $orderRepo = new OrderRepository($db);
            
            $dashboardService = new DashboardService($bookRepo, $orderRepo);
            $stats = $dashboardService->getDashboardData();

            render('dashboard/index', [
                'title' => 'Dashboard',
                'stats' => $stats
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }
}