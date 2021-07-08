<?php


namespace App\Controllers\Admin;


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
        $photos = $this->database->all('photos');

        echo $this->view->render('admin/photos/index', ['photos' => $photos]);
    }

    public function create()
    {
        $categories = $this->database->all('categories');

        echo $this->view->render('admin/photos/create', ['categories' => $categories]);
    }

    public function store()
    {
        $validator = Validator::key('title', Validator::stringType()->notEmpty());
        $this->validate($validator, $_POST, [
            'title' => 'Заполните поле Название'
        ]);
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

        return redirect('/admin/photos');
    }

    public function edit($id)
    {
        $photo = $this->database->find('photos', $id);
        $categories = $this->database->all('categories');

        echo $this->view->render('admin/photos/edit',[
            'categories' => $categories, 'photo' => $photo
        ]);
    }

    public function update($id)
    {
        $validator = Validator::key('title', Validator::stringType()->notEmpty());
        $this->validate($validator, $_POST, [
            'title' => 'Заполните поле Название'
        ]);
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

        return redirect('/admin/photos');
    }

    public function delete($id)
    {
        $photo = $this->database->find('photos', $id);
        $this->imageManager->deleteImage($photo['image']);
        $this->database->delete('photos', $id);

        return back();
    }

    private function validate($validator, $data, $message)
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