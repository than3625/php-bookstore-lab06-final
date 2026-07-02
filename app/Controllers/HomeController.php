<?php

class HomeController
{
    public function index(): void
    {
        try {
            if (is_logged_in()) {
                redirect('/dashboard');
                return;
            }
            render('home', ['view' => 'home', 'title' => 'Home']);
        } catch (Exception $e) {
            http_response_code(500);
            render('errors/500', ['title' => 'Server Error']);
        }
    }
}