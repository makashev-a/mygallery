<?php


namespace App\Controllers\Admin;


class HomeController extends MainController
{
    public function index()
    {
        $admin = $this->database->find('users', $this->auth->getUserId());

        echo $this->view->render('admin/dashboard', ['admin' => $admin]);
    }
}