<?php


namespace App\Controllers;

use Aura\SqlQuery\QueryFactory;
use PDO;

class HomeController extends MainController
{

    private $queryFactory;
    private $pdo;

    public function __construct(QueryFactory $queryFactory, PDO $pdo)
    {
        parent::__construct();
        $this->queryFactory = $queryFactory;
        $this->pdo = $pdo;
    }

    public function index()
    {
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = 8;
        $totalItems = count($this->database->all('photos'));
        $urlPattern = '/?page=(:num)';

        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from('photos')
            ->page($currentPage)
            ->setPaging($itemsPerPage);

        $statement = $this->pdo->prepare($select->getStatement());
        $statement->execute($select->getBindValues());
        $photos =  $statement->fetchAll(PDO::FETCH_ASSOC);

        $paginator = paginate(
            $totalItems,
            $currentPage,
            $itemsPerPage,
            $urlPattern
        );

        echo $this->view->render('home', ['photos' => $photos, 'paginator' => $paginator]);
    }

    public function category($id)
    {
        $pagination = $this->paginator->setPagination('photos', 'category_id', $id, 'category');

        $category = $this->database->find('categories', $id);

        echo $this->view->render('category', [
            'photos' => $pagination['paginateTable'],
            'paginator' => $pagination['paginator'],
            'category' => $category
        ]);
    }

    public function user($id)
    {
        $pagination = $this->paginator->setPagination('photos', 'user_id', $id, 'user');

        $user = $this->database->find('users', $id);

        echo $this->view->render('user', [
            'photos' => $pagination['paginateTable'],
            'user' => $user,
            'paginator' => $pagination['paginator']
        ]);
    }
}