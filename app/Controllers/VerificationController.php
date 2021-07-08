<?php


namespace App\Controllers;


class VerificationController extends MainController
{
    public function showForm()
    {
        echo $this->view->render('auth/verification-form');
    }

    public function verify()
    {
        try {
            $this->auth->confirmEmail($_GET['selector'], $_GET['token']);

            flash()->success(['Ваш email подтвержден, теперь вы можете войти!']);
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            flash()->error(['Неверный токен']);
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            flash()->error(['Токен испортился']);
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error(['Email уже существует']);
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error(['Слишком много запросов!']);
        }

        return redirect('/login');
    }
}