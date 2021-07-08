<?php


namespace App\Controllers;


use App\Models\Database;
use App\Models\Paginator;
use App\Models\Roles;
use Delight\Auth\Auth;
use League\Plates\Engine;

class MainController
{
    protected $auth;
    protected $view;
    protected $database;
    protected $paginator;

    public function __construct()
    {
        $this->auth = components(Auth::class);
        $this->view = components(Engine::class);
        $this->database = components(Database::class);
        $this->paginator = components(Paginator::class);
    }

    public function checkForAccess()
    {
        if ($this->auth->hasRole(Roles::USER)) {
            return redirect('/');
        }
    }

    public function checkForLogin()
    {
        if (!$this->auth->isLoggedIn()) {
            return redirect('/');
        }
    }
}