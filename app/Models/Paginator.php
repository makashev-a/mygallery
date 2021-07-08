<?php


namespace App\Models;


class Paginator
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function setPagination($table, $row, $id, $urlPart)
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $perPage = 8;
        $paginateTable = $this->database->getPaginatedFrom($table, $row, $id, $page, $perPage);
        $paginator = paginate(
            $this->database->getCount($table, $row, $id),
            $page,
            $perPage,
            "/$urlPart/$id?page=(:num)"
        );

        $paginatorAlt = paginate(
            $this->database->getCount($table, $row, $id),
            $page,
            $perPage,
            "/$urlPart?page=(:num)"
        );

        return [
            'paginateTable' => $paginateTable,
            'paginator' =>  $paginator,
            'paginatorAlt' => $paginatorAlt
        ];
    }
}