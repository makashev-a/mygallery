<?php


namespace App\Controllers;


use App\Models\Mail;
use App\Models\Profile;

class ProfileController extends MainController
{
    private $mail;
    private $profile;

    public function __construct(Mail $mail, Profile $profile)
    {
        parent::__construct();
        $this->mail = $mail;
        $this->profile = $profile;
    }

    public function showInfo()
    {
        $this->checkForLogin();

        $user = $this->database->find('users', $this->auth->getUserId());

        echo $this->view->render('profile/info', compact('user'));
    }

    public function showSecurity()
    {
        $this->checkForLogin();

        echo $this->view->render('profile/security');
    }

    public function postInfo()
    {
        try {
            $this->profile->changeInformation($_POST['email'], $_FILES['image'], $_POST['username']);
            flash()->success(['Профиль обновлен']);
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error(['Неверный формат почты']);
            // invalid email address
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            // email address already exists
            flash()->error(['Почта уже существует']);
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            // account not verified
            flash()->error(['Почта не подтверждена']);
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            // not logged in
            flash()->error(['Вы не авторизованы']);
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
            flash()->error(['Слишком много запросов!']);
        }
        return back();
    }

    public function postSecurity()
    {
        try {
            $this->auth->changePassword($_POST['password'], $_POST['new_password']);
            flash()->success(['Ваш пароль успешно изменен']);
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            // not logged in
            flash()->error(['Вы не авторизованы!']);
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            // invalid password(s)
            flash()->error(['Неправильный пароль!']);
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
            flash()->error(['Слишком много запросов!']);
        }

        return back();
    }
}