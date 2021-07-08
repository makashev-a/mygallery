<?php


namespace App\Controllers;


use App\Models\Notifications;

class ResetPasswordController extends MainController
{
    private $notifications;

    public function __construct(Notifications $notifications)
    {
        parent::__construct();
        $this->notifications = $notifications;
    }

    public function showForm()
    {
        echo $this->view->render('auth/password-recovery');
    }

    public function recovery()
    {
        try {
            $this->auth->forgotPassword($_POST['email'], function ($selector, $token) {
                $this->notifications->passwordReset($_POST['email'], $selector, $token);
                flash()->success(['Код сброса пароля был отправлен вам на почту.']);
            });
        } catch (\Delight\Auth\InvalidEmailException $e) {
            // invalid email address
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            // email not verified
        } catch (\Delight\Auth\ResetDisabledException $e) {
            // password reset is disabled
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
        }
        return back();
    }

    public function showSetForm()
    {
        if (empty($_GET)) {
            return redirect('/');
        }
        try {
            $this->auth->canResetPasswordOrThrow($_GET['selector'], $_GET['token']);
            echo $this->auth->render('auth/password-set', ['data' => $_GET]);
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            die('Неверный токен');
        } catch (\Delight\Auth\TokenExpiredException $e) {
            die('Токен просрочен');
        } catch (\Delight\Auth\ResetDisabledException $e) {
            die('Изменение пароля отключено');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Превышен лимит запросов');
        }
    }

    public function change()
    {
        try {
            $this->auth->resetPassword($_POST['selector'], $_POST['token'], $_POST['password']);

            flash()->success(['Ваш пароль успешно изменен!']);
            return redirect('/login');
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            flash()->error(['Неверный токен']);
        } catch (\Delight\Auth\TokenExpiredException $e) {
            flash()->error(['Токен просрочен']);
        } catch (\Delight\Auth\ResetDisabledException $e) {
            flash()->error(['Изменение пароля отключено']);
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error(['Введите пароль']);
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error(['Превышен лимит запросов.']);
        }

        return back();
    }
}
