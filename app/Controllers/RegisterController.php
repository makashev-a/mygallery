<?php


namespace App\Controllers;


use App\Models\Registration;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;

class RegisterController extends MainController
{
    private $registration;

    public function __construct(Registration $registration)
    {
        parent::__construct();
        $this->registration = $registration;
    }

    public function showForm()
    {
        echo $this->view->render('auth/register');
    }

    public function register()
    {
        $this->validate();
        try {
            $this->registration->make(
                $_POST['email'],
                $_POST['password'],
                $_POST['username']
            );
            flash()->success(['На вашу почту ' . $_POST['email'] . ' была отправлена ссылка для активации.']);
            return redirect('/login');
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error(['Неправильный email']);
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error(['Неправильный пароль']);
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error(['Пользователь уже существует']);
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error(['Слишком много раз пытаетесь зарегаться']);
        }

        return redirect('/register');
    }

    private function validate()
    {
        $validator = Validator::key('username', Validator::stringType()->notEmpty())
            ->key('email', Validator::email())
            ->key('password', Validator::stringType()->notEmpty())
            ->key('terms', Validator::trueVal())
            ->keyValue('password_confirmation', 'equals', 'password');

        try {
            $validator->assert($_POST);
        } catch (ValidationException $exception) {
            $exception->getMessages($this->getErrors());
            flash()->error($exception->getMessages($this->getErrors()));

            return redirect('register');
        }
    }

    private function getErrors()
    {
        return [
            'terms'   =>  'Вы должны согласиться с правилами.',
            'username' => 'Введите имя',
            'email' => 'Неверный формат e-mail',
            'password'  =>  'Введите пароль',
            'password_confirmation' =>  'Пароли не сопадают'
        ];
    }
}