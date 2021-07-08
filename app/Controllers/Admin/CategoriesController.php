<?php


namespace App\Controllers\Admin;


use App\Controllers\MainController;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;

class CategoriesController extends MainController
{
    public function index()
    {
        $categories = $this->database->all('categories');

        echo $this->view->render('admin/categories/index', ['categories' => $categories]);
    }

    public function create()
    {
        echo $this->view->render('admin/categories/create');
    }

    public function store()
    {
        $validator = Validator::key('title', Validator::stringType()->notEmpty());
        $this->validate($validator, $_POST, [
            'title' => 'Заполните поле Название'
        ]);

        $this->database->create('categories', $_POST);

        return redirect('/admin/categories');
    }

    public function edit($id)
    {
        $category = $this->database->find('categories', $id);

        echo $this->view->render('admin/categories/edit', ['category' => $category]);
    }

    public function update($id)
    {
        $validator = Validator::key('title', Validator::stringType()->notEmpty());
        $this->validate($validator, $_POST, [
            'title' => 'Заполните поле Название'
        ]);

        $this->database->update('categories', $id, $_POST);

        return redirect('/admin/categories');
    }

    public function delete($id)
    {
        $this->database->delete('categories', $id);
        return back();
    }

    public function validate($validator, $data, $message)
    {
        try {
            $validator->assert($data);
        } catch (ValidationException $exception) {
            $exception->getMessages($message);
            flash()->error($exception->getMessages($message));

            return back();
        }
    }
}