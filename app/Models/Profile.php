<?php


namespace App\Models;


use Delight\Auth\Auth;

class Profile
{
    private $auth;
    private $mail;
    private $database;
    private $imageManager;
    private $notifications;

    public function __construct(Auth $auth, Mail $mail, Database $database, ImageManager $imageManager, Notifications $notifications)
    {
        $this->auth = $auth;
        $this->mail = $mail;
        $this->database = $database;
        $this->imageManager = $imageManager;
        $this->notifications = $notifications;
    }

    public function changeInformation($newEmail, $newImage, $newUsername = null)
    {
        if ($this->auth->getEmail() != $newEmail) {
            $this->auth->changeEmail($newEmail, function ($selector, $token) use ($newEmail) {
                $this->notifications->emailWasChanged($newEmail, $selector, $token);
                flash()->success(['На вашу почту ' . $newEmail . ' была отправлена ссылка для активации']);
            });
        }

        $user = $this->database->find('users', $this->auth->getUserId());

        $image = $this->imageManager->uploadImage($newImage, $user['image']);

        $this->database->update('users', $this->auth->getUserId(), [
            'username' => isset($newUsername) ? $newUsername : $this->auth->getUsername(),
            'image' => $image
        ]);
    }
}