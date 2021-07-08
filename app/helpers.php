<?php

use App\Models\Database;
use App\Models\Roles;
use Delight\Auth\Auth;
use Illuminate\Support\Arr;
use JasonGrimes\Paginator;
use League\Plates\Engine;

function view($path, $parameters = [])
{
    global $container;
    $plates = $container->get('plates');
    echo $plates->render($path, $parameters);
}

function back()
{
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

function redirect($path)
{
    header("Location: $path");
    exit;
}

function auth()
{
    global $container;
    return $container->get(Auth::class);
}

function config($field)
{
    $config = require '../app/config.php';
    return Arr::get($config, $field);
}

function components($name)
{
    global $container;
    return $container->get($name);
}

function getImage($image)
{
    return (new \App\Models\ImageManager())->getImage($image);
}

function getUserAvatar($id)
{
    global $container;
    $queryFactory = $container->get(\Aura\SqlQuery\QueryFactory::class);
    $pdo = $container->get(PDO::class);
    $imageManager = $container->get(\App\Models\ImageManager::class);
    $database = new Database($pdo, $queryFactory);
    $result = $database->find('users', $id);
    return $imageManager->getImage($result['image']);
}

function getAllCategories()
{
    global $container;
    $queryFactory = $container->get(\Aura\SqlQuery\QueryFactory::class);
    $pdo = $container->get(PDO::class);
    $database = new Database($pdo, $queryFactory);
    return $database->all('categories');
}

function getCategory($id)
{
    global $container;
    $queryFactory = $container->get('Aura\SqlQuery\QueryFactory');
    $pdo = $container->get('PDO');
    $database = new Database($pdo, $queryFactory);
    return $database->find('categories', $id);
}

function uploadedDate($timestamp)
{
    return date('d.m.Y', strtotime($timestamp));
}

function abort($type)
{
    switch ($type) {
        case 404:
            $view = components(Engine::class);
            echo $view->render('errors/404');
            exit;
    }
}

function getRole($key)
{
    return Roles::getRole($key);
}

function paginator($paginator)
{
    include config('views_path') . 'inc/pagination.php';
}

function paginate($count, $page, $perPage, $url)
{
    $totalItems = $count;
    $currentPage = $page;
    $itemsPerPage = $perPage;
    $urlPattern = $url;

    $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
    return $paginator;
}