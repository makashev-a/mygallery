<?php


namespace App\Controllers\Admin;


use App\Models\Roles;

class MainController extends \App\Controllers\MainController
{
    public function __construct()
    {
        parent::__construct();

        if(!$this->auth->hasRole(Roles::ADMIN)) {
            abort(404);
        }
    }
}