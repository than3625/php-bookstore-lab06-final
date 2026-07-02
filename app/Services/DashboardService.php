<?php
class DashboardService
{
    public function __construct(
        private BookRepository $bookRepo,
        private OrderRepository $orderRepo
    ) {}

    public function getDashboardData(): array
    {
        return [
            'available_count' => $this->bookRepo->getCountByStatus('available'),
            'sold_out_count'  => $this->bookRepo->getCountByStatus('out_of_stock'),
            'total_orders'    => $this->orderRepo->countAll(),
            
            'available_books' => $this->bookRepo->getByStatus('available'),
            'sold_out_books'  => $this->bookRepo->getByStatus('out_of_stock')
        ];
    }
}