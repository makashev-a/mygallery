<?php


namespace App\Controllers;


use App\Models\ImageManager;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;

class PhotosController extends MainController
{
    private $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        parent::__construct();
        $this->imageManager = $imageManager;
    }

    public function index()
    {
        $this->checkForLogin();

        $pagination = $this->paginator->setPagination('photos', 'user_id', $this->auth->getUserId(), 'photos');

        echo $this->view->render('photos/index', ['photos' => $pagination['paginateTable'], 'paginator' => $pagination['paginatorAlt']]);
    }

    public function create()
    {
        $this->checkForLogin();

        $categories = $this->database->all('categories');

        echo $this->view->render('photos/create', ['categories' => $categories]);
    }

    public function store()
    {
        $validator = Validator::key('title', Validator::stringType()->notEmpty())
            ->key('description', Validator::stringType()->notEmpty())
            ->key('category_id', Validator::intVal())
            ->keyNested('image.tmp_name', Validator::image());

        $this->validate($validator);

        $image = $this->imageManager->uploadImage($_FILES['image']);
        $dimensions = $this->imageManager->getDimensions($image);
        $data = [
            'image' => $image,
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category_id' => $_POST['category_id'],
            'user_id' => $this->auth->getUserId(),
            'dimensions' => $dimensions,
            'date' => date('Y-m-d h:i:s', time())
        ];
        $this->database->create('photos', $data);

        flash()->success(['Спасибо, Ваша картинка загружена :)']);

        return back();
    }

    public function edit($id)
    {
        $this->checkForLogin();

        $photo = $this->database->find('photos', $id);
        if ($photo['user_id'] != $this->auth->getUserId()) {
            flash()->error('Нельзя редактировать чужие фотографии!');
            return redirect('/photos');
        }

        $categories = $this->database->all('categories');
        echo $this->view->render('photos/edit', ['photo' => $photo, 'categories' => $categories]);
    }

    public function update($id)
    {
        $validator = Validator::key('title', Validator::stringType()->notEmpty())
            ->key('description', Validator::stringType()->notEmpty())
            ->key('category_id', Validator::intVal())
            ->keyNested('image.tmp_name', Validator::optional(Validator::image()));

        $this->validate($validator);

        $photo = $this->database->find('photos', $id);
        $image = $this->imageManager->uploadImage($_FILES['image'], $photo['image']);
        $dimensions = $this->imageManager->getDimensions($image);

        $data = [
            'image' => $image,
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category_id' => $_POST['category_id'],
            'user_id' => $this->auth->getUserId(),
            'dimensions' => $dimensions
        ];

        $this->database->update('photos', $id, $data);

        flash()->success(['Ваша запись успешно обновлена']);

        return back();
    }

    public function show($id)
    {
        $photo = $this->database->find('photos', $id);
        $user = $this->database->find('users', $photo['user_id']);
        $userImages = $this->database->whereAll('photos', 'user_id', $user['id'], 4);

        echo $this->view->render('photos/show', [
            'photo' => $photo,
            'user' => $user,
            'userImages' => $userImages
        ]);
    }

    public function delete($id)
    {
        $photo = $this->database->find('photos', $id);
        if ($photo['user_id'] != $this->auth->getUserId()) {
            flash()->error(['Нельзя редактировать чужие фотографии!']);
            return redirect('/photos');
        }
        $this->imageManager->deleteImage($photo['image']);
        $this->database->delete('photos', $id);

        return back();
    }

    public function download($id)
    {
        $photo = $this->database->find('photos', $id);
        echo $this->view->render('photos/download', [
            'photo' => $photo
        ]);
    }

    private function validate($validator)
    {
        try {
            $validator->assert(array_merge($_POST, $_FILES));
        } catch (ValidationException $exception) {
            $exception->getMessages($this->getErrors());
            flash()->error($exception->getMessages($this->getErrors()));

            return back();
        }
    }

    private function getErrors()
    {
        return [
            'title' => 'Введите название',
            'description' => 'Введите описание',
            'category_id' => 'Выберите категорию',
            'image.tmp_name' => 'Неверный формат картинки'
        ];
    }
}