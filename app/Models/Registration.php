<?php


namespace App\Models;


use Delight\Auth\Auth;

class Registration
{
    private $auth;
    private $database;
    private $notifications;

    public function __construct(Auth $auth, Database $database, Notifications $notifications)
    {
        $this->auth = $auth;
        $this->database = $database;
        $this->notifications = $notifications;
    }

    public function make($email, $password, $username)
    {
        $userId = $this->auth->register($email, $password, $username, function ($selector, $token) use($email) {
            $this->notifications->emailWasChanged($email, $selector, $token);
        });

        $this->database->update('users', $userId, ['roles_mask' => Roles::USER]);

        return $userId;
    }

    public function verify($selector, $token)
    {
        return $this->auth->confirmEmail($selector, $token);
    }
}